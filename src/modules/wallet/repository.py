from mongoengine import *
from datetime import datetime
from bson import ObjectId


class WalletRepositroy(Document):

    members = ListField(required=False, defalt=[])
    name = StringField(required=True)
    walletId = StringField(default=str(
        datetime.timestamp(datetime.now())), unique=True)
    owner = ObjectIdField(required=True, unique_with="currency")
    balance = IntField(default=0, required=True)
    default = BooleanField(required=True, default=False)
    currency = StringField(required=True)
    createAt = DateTimeField(default=datetime.now())
    updatedAt = DateTimeField(default=None)

    meta = {
        "collection": "wallets"
    }

    def new(payload: dict):
        return WalletRepositroy(**payload).save()

    def find(walletId, dates):
        return WalletRepositroy.objects().aggregate([
            {
                "$match": {
                    "_id": ObjectId(walletId)
                }
            },
            {
                "$lookup": {
                    "from": "wallet_history",
                    "localField": "_id",
                    "foreignField": "walletId",
                    "as": "history"
                }
            },
 


        ])

    def byOwner(owner_id: str):
        return WalletRepositroy.objects().aggregate([
            {
                "$match": {
                    "owner": ObjectId(owner_id)
                }
            },
            {
                "$project": {
                    "_id": {
                        "$toString": "$_id"
                    },
                    "owner": {
                        "$toString": "$owner"
                    },
                    "balance": 1,
                    "members": 1,
                    "currency": 1,
                    "default": 1,
                    "name": 1,
                    "walletId": 1,
                }
            }
        ])
