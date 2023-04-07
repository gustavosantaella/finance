from fastapi import FastAPI
from database.mongo.main import connect_db
from src.routes import main as main_routes
from dotenv import load_dotenv
from uvicorn import run
load_dotenv()

# global MAX_USER


# cretae app
app = FastAPI()


main_routes(app)

connect_db()


    
