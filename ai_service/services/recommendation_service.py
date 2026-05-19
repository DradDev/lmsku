import os
import joblib
import pandas as pd
from datetime import datetime
from dotenv import load_dotenv
from sqlalchemy import text

from database import get_engine

load_dotenv()

FEATURE_COLS = [
    "item_type_encoded",
    "user_avg_skill_score",
    "user_lowest_skill_score",
    "user_completed_course_count",
    "user_completed_project_count",
    "user_recent_activity_score",
    "user_top_interest_tag_id",
    "item_difficulty_level",
    "item_main_skill_id",
    "item_popularity_score",
    "item_completion_rate",
    "interest_match_score",
    "weakness_match_score",
    "readiness_score",
]


def get_model_path():
    model_path = os.getenv(
        "MODEL_PATH",
        "models/random_forest_recommendation_model.pkl"
    )

    base_dir = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))

    if os.path.isabs(model_path):
        return model_path

    return os.path.join(base_dir, model_path)


def load_model():
    model_path = get_model_path()

    if not os.path.exists(model_path):
        raise FileNotFoundError(f"Model file tidak ditemukan: {model_path}")

    return joblib.load(model_path)


def get_latest_snapshot_date(engine):
    with engine.connect() as connection:
        row = connection.execute(
            text("SELECT MAX(snapshot_date) AS snapshot_date FROM recommendation_feature_snapshots")
        ).fetchone()

    return row.snapshot_date if row else None


def generate_recommendations(snapshot_date=None, top_n=10):
    engine = get_engine()

    if snapshot_date is None:
        snapshot_date = get_latest_snapshot_date(engine)

    if snapshot_date is None:
        return {
            "status": "error",
            "message": "Tidak ada snapshot_date di recommendation_feature_snapshots."
        }

    query = """
        SELECT
            snapshot_date,
            user_id,
            item_type,
            item_id,
            item_type_encoded,
            user_avg_skill_score,
            user_lowest_skill_score,
            user_completed_course_count,
            user_completed_project_count,
            user_recent_activity_score,
            user_top_interest_tag_id,
            item_difficulty_level,
            item_main_skill_id,
            item_popularity_score,
            item_completion_rate,
            interest_match_score,
            weakness_match_score,
            readiness_score,
            label_taken
        FROM recommendation_feature_snapshots
        WHERE snapshot_date = :snapshot_date
    """

    df = pd.read_sql(
        text(query),
        engine,
        params={"snapshot_date": snapshot_date}
    )

    if df.empty:
        return {
            "status": "error",
            "message": f"Tidak ada data snapshot untuk tanggal {snapshot_date}."
        }

    missing_features = [col for col in FEATURE_COLS if col not in df.columns]

    if missing_features:
        return {
            "status": "error",
            "message": f"Feature tidak lengkap: {missing_features}"
        }

    model = load_model()

    X = df[FEATURE_COLS].fillna(0)

    df["prediction_score"] = model.predict_proba(X)[:, 1]

    candidates = df[df["label_taken"] == 0].copy()

    if candidates.empty:
        return {
            "status": "warning",
            "message": "Tidak ada kandidat rekomendasi karena semua item sudah label_taken = 1.",
            "snapshot_date": str(snapshot_date),
            "total_snapshot_rows": len(df),
            "inserted_rows": 0
        }

    candidates = candidates.sort_values(
        ["user_id", "prediction_score"],
        ascending=[True, False]
    )

    top_recommendations = (
        candidates
        .groupby("user_id")
        .head(top_n)
        .copy()
    )

    top_recommendations["rank"] = (
        top_recommendations
        .groupby("user_id")
        .cumcount() + 1
    )

    now = datetime.now()

    result_df = top_recommendations[
        [
            "snapshot_date",
            "user_id",
            "item_type",
            "item_id",
            "prediction_score",
            "rank",
        ]
    ].copy()

    result_df["model_name"] = "Random Forest"
    result_df["model_version"] = "v1"
    result_df["created_at"] = now
    result_df["updated_at"] = now

    with engine.begin() as connection:
        connection.execute(
            text("""
                DELETE FROM recommendation_results
                WHERE snapshot_date = :snapshot_date
                  AND model_name = :model_name
                  AND model_version = :model_version
            """),
            {
                "snapshot_date": snapshot_date,
                "model_name": "Random Forest",
                "model_version": "v1",
            }
        )

    result_df.to_sql(
        "recommendation_results",
        engine,
        if_exists="append",
        index=False
    )

    return {
        "status": "ok",
        "message": "Recommendation batch berhasil dijalankan.",
        "snapshot_date": str(snapshot_date),
        "total_snapshot_rows": int(len(df)),
        "candidate_rows": int(len(candidates)),
        "inserted_rows": int(len(result_df)),
        "top_n": int(top_n)
    }