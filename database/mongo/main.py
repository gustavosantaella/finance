from mongoengine import connect
from pymongo import MongoClient
from config import env
 
def connect_db():
    try:
        DB_NAME = env("MONGO_DATABASE")
        URI = env("MONGO_URI")
        print(DB_NAME, URI)
        db = connect(db=DB_NAME, host=URI)
        # client = MongoClient(URI)
        # db = client.get_database(DB_NAME)
        print("Connected to database")
    except Exception as e:
        print("Error to connect database", e)