from src.helpers import EnumHelper
from pydantic import *

class HistoryType(EnumHelper):
    expense = 'expense'
    income = 'income'
    withdraw = 'withdraw'
    
    
class HistoryDTO(BaseModel):
    transactionId: str = None
    type: HistoryType
    description: str = None
    provider: str = None
    categoryId: str = None
    walletId: str = None
    value: int
    
    