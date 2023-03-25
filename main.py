from fastapi import FastAPI
from database.mongo.main import connect_db
from src.routes import main as main_routes


# cretae app
app = FastAPI()

main_routes(app)

connect_db()

