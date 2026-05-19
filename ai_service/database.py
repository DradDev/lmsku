import os
from dotenv import load_dotenv
from sqlalchemy import create_engine, text

load_dotenv()


def get_database_url():
    db_host = os.getenv("DB_HOST", "127.0.0.1")
    db_port = os.getenv("DB_PORT", "3306")
    db_name = os.getenv("DB_DATABASE", "lmsku")
    db_user = os.getenv("DB_USERNAME", "root")
    db_password = os.getenv("DB_PASSWORD", "")

    return f"mysql+pymysql://{db_user}:{db_password}@{db_host}:{db_port}/{db_name}"


def get_engine():
    database_url = get_database_url()

    engine = create_engine(
        database_url,
        pool_pre_ping=True,
        future=True
    )

    return engine


def test_database_connection():
    engine = get_engine()

    with engine.connect() as connection:
        result = connection.execute(text("SELECT 1 AS status"))
        row = result.fetchone()

    return row.status == 1