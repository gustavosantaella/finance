from mongoengine import *
from datetime import datetime
from math import floor
from .dto import HistoryType


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
        "collection" : "wallet_history"
    }
    
    
    def new(data: dict):
        return FinancialHistoryRepository(**data).save()
    
    def getByWalletId(id: str):
        return FinancialHistoryRepository.objects(walletId=id).all()
    
    