from fastapi import APIRouter
from src.helpers import response
from .service import WalletService
from src.modules.auth.guard import AuthRole
from fastapi import Request

router = APIRouter(prefix='/wallet')


@router.get('/by-owner')
@AuthRole(['customer'])
def getByOwner(request: Request):
    try:
        data = WalletService.getAllByOwnerId(request.profile['userId'])
        return response(content=data)
    except Exception as e:
        return response(error=str(e))
    
@router.get("/{walletId}")
@AuthRole(['customer'])
def balance(request: Request, walletId: str, from_date: str = '', to_date :str = ''):
    try:
        data = WalletService.balance(walletId, [from_date, to_date]) 
        return response(content=data)
    except Exception as e:
        return response(error=str(e))
