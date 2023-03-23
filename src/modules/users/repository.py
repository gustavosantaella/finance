from mongoengine import *
from datetime import datetime

class UserRepositoy(Document):
    email = StringField(required=True, unique=True)
    password = StringField(required=True)
    created_at = DateTimeField(default=datetime.now(), required=True)
    updated_at = DateTimeField(default=None)

    meta = {
        "collection": "users"
    }
    
    
    def new(data: dict):
        return UserRepositoy(**data).save()
    
    def findByEmail(email: str):
        return UserRepositoy.objects(email=email).first()