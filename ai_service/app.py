from flask import Flask, jsonify, request
from dotenv import load_dotenv
from sqlalchemy import text
import os

from database import get_engine, test_database_connection
from services.recommendation_service import generate_recommendations, load_model

load_dotenv()

app = Flask(__name__)

APP_SECRET_TOKEN = os.getenv("APP_SECRET_TOKEN", "lms_ai_secret_token")


@app.get("/")
def index():
    return jsonify({
        "service": "LMS AI Recommendation Service",
        "status": "running"
    })


@app.get("/health")
def health():
    return jsonify({
        "status": "ok",
        "message": "AI service is healthy"
    })


@app.get("/db-test")
def db_test():
    try:
        is_connected = test_database_connection()

        engine = get_engine()

        with engine.connect() as connection:
            snapshot_count = connection.execute(
                text("SELECT COUNT(*) AS total FROM recommendation_feature_snapshots")
            ).fetchone().total

            result_count = connection.execute(
                text("SELECT COUNT(*) AS total FROM recommendation_results")
            ).fetchone().total

        return jsonify({
            "status": "ok",
            "database_connected": is_connected,
            "recommendation_feature_snapshots_count": snapshot_count,
            "recommendation_results_count": result_count
        })

    except Exception as error:
        return jsonify({
            "status": "error",
            "message": str(error)
        }), 500


@app.get("/model-test")
def model_test():
    try:
        model = load_model()

        return jsonify({
            "status": "ok",
            "message": "Model berhasil diload.",
            "model_type": str(type(model))
        })

    except Exception as error:
        return jsonify({
            "status": "error",
            "message": str(error)
        }), 500


@app.post("/recommendations/run-batch")
def run_batch_recommendations():
    token = request.headers.get("X-AI-TOKEN")

    if token != APP_SECRET_TOKEN:
        return jsonify({
            "status": "error",
            "message": "Unauthorized"
        }), 401

    snapshot_date = request.json.get("snapshot_date") if request.is_json else None
    top_n = request.json.get("top_n", 10) if request.is_json else 10

    try:
        result = generate_recommendations(
            snapshot_date=snapshot_date,
            top_n=int(top_n)
        )

        status_code = 200 if result.get("status") in ["ok", "warning"] else 400

        return jsonify(result), status_code

    except Exception as error:
        return jsonify({
            "status": "error",
            "message": str(error)
        }), 500


if __name__ == "__main__":
    app.run(
        host="127.0.0.1",
        port=5000,
        debug=True
    )