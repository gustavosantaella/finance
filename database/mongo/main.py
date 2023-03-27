from mongoengine import connect
from config import env
def connect_db():
    try:
        DB_NAME = env("MONGO_DATABASE")
        URI = env("MONGO_URI")
        connect(db=DB_NAME, host=URI)
        print("Connected to database")
    except Exception as e:
        print("Error to connect database", e)