from mongoengine import connect

def connect_db():
    try:
        connect(db='finance_app', host='mongodb://localhost:27017/finance_app')
        print("Connected to database")
    except Exception as e:
        print("Error to connect database", e)