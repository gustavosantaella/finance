from fastapi import FastAPI
from database.mongo.main import connect_db
from src.routes import main as main_routes
from dotenv import load_dotenv
from uvicorn import run
load_dotenv()


def app():
    # cretae app
    app = FastAPI()

    main_routes(app)

    connect_db()

if __name__ == "__main__":
    run("main:app")
    
