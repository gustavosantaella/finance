from mongoengine import *
from datetime import datetime
from src.modules.auth.v1.auth_dto import RolesDTO
from mongoengine import *
from bson import ObjectId


class UserRepositoy(Document):
    email = StringField(required=True, unique=True)
    roles = ListField(EnumField(RolesDTO, default=RolesDTO.customer), required=True)
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
    
    def check_roles(user_id: str, roles: list):
        return list(UserRepositoy.objects().aggregate([
            {
                "$match":{
                    "_id": ObjectId(user_id)
                }
            },
            {
                "$unwind":"$roles"
            },
            {
                "$match":{
                    "roles":{
                        "$in": roles
                    }
                }
            }
        ]))