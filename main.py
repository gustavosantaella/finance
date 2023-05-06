from fastapi import FastAPI
from database.mongo.main import connect_db
from src.routes import main as main_routes
from dotenv import load_dotenv
from uvicorn import run
import asyncio
load_dotenv()

from database.mongo.main import connect_db

connect_db()    

# cretae app
app = FastAPI(
    title="Finance API"
)


main_routes(app)






