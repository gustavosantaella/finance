from mongoengine import *
from datetime import datetime
from math import floor
from .dto import HistoryType
from bson import ObjectId


class WalletHistoryField(Document):
    prevBalance = FloatField()


class GatewayField(DictField):
    provider = StringField(default='WAFI')


class FinancialHistoryRepository(Document):
    walletId = ObjectIdField()
    wallet = WalletHistoryField()
    description = StringField()
    value = FloatField()
    gateway = GatewayField()
    historyId = StringField(default=str(floor(
        datetime.timestamp(datetime.now()))), required=True)
    transactionId = StringField()
    type = EnumField(HistoryType)
    categoryId = ObjectIdField()
    createdBy = DictField(default={})
    createdAt = StringField(default=str(datetime.now()))
    updatedAt = DateTimeField()

    meta = {
        "collection": "wallet_history"
    }

    def new(data: dict):
        return FinancialHistoryRepository(**data).save()

    def getByWalletId(id: str, month=None, date=None):
        pipeline = [
            {
                "$addFields": {
                    "date": {
                        "$dateFromString": {
                            "dateString": "$createdAt"
                        }
                    }
                }
            },
        ]
        if month:
            pipeline = [
                *pipeline,

                {
                    "$match": {
                        "$expr": {
                            "$eq": [{"$month": "$date"}, int(month)]
                        }
                    }
                }
            ]

            pipeline = [
                *pipeline,
                {
                    "$match": {
                        "walletId": ObjectId(id),

                    }
                }
            ]

        if date:
            pipeline = [
                *pipeline,
                {
                    "$addFields": {
                        "dateString": {
                            "$dateToString": {
                                "date": "$date",
                                "format": "%Y-%m-%d"
                            }
                        }
                    }
                },
                {
                    "$match": {
                        "dateString": datetime.strftime(datetime.strptime(date, "%Y-%m-%d"), "%Y-%m-%d")
                    }
                }
            ]
        
        pipeline = [
            *pipeline,
            {
                "$match":{
                     "walletId": ObjectId(id),
                }
            }
        ]
        return FinancialHistoryRepository.objects().aggregate(pipeline)

    def detail(walletId, historyId):
        data = FinancialHistoryRepository.objects().aggregate([
            {
                "$match": {
                    "walletId": ObjectId(walletId),
                    "_id": ObjectId(historyId),
                }
            },
            {
                "$lookup": {
                    "from": "categories",
                    "foreignField": "_id",
                    "localField": "categoryId",
                    'as': "categories"
                }
            },
            {
                "$unwind": "$categories"
            },
            {
                "$set":{
                    "categories._id":{
                        "$toString":"$categories._id"
                    },
                   "walletId":{
                        "$toString":"$walletId"
                    },
                   "_id":{
                        "$toString":"$_id"
                    },
                }
            }
        ])
        
        return data
