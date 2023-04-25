from fastapi import APIRouter
from src.helpers import response
from .service import FinancialHistoryService
from .dto import HistoryDTO
from fastapi import Request
from src.modules.auth.guard import AuthRole

router = APIRouter(prefix='/financial/history')

@router.post('/')
@AuthRole(['customer'])
def add(request: Request, body: HistoryDTO):
    try:
        data = FinancialHistoryService.add(body)
        return response(content=data)
    except Exception as e:
        return response(error=str(e), status=400)

@router.get("/{walletId}")
@AuthRole(['customer'])
def getByWallet(request: Request, walletId: str, month = None, date = None):
    try:
        data = FinancialHistoryService.getHistoryByWalletId(walletId, month, date)
        return response(content=data)
    except Exception as e:
        return response(error=str(e), status=400)
    
@router.get("/{walletId}/{historyId}")
@AuthRole(['customer'])
def historyDetail(request: Request, walletId: str, historyId):
    try:
        print(walletId, historyId)
        data = FinancialHistoryService.historyDetail(walletId, historyId)
        return response(content=data)
    except Exception as e:
        print(e)
        return response(error=str(e), status=400)