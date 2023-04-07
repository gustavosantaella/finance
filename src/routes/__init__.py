from src.modules.auth.api_controller import router as auth_router
from src.modules.categories.api import router as category_router
from src.modules.financials.history.api import router as history_router
from src.modules.wallet.api import router as wallet_router
from fastapi import APIRouter

def main(ctx: APIRouter):
    
    @ctx.get('/health')
    def status():
        return {
            "status":True
        }
    """
        Api version
        """ 
    api = APIRouter(prefix="/api")

    api.include_router(router=auth_router)
    api.include_router(router=category_router)
    api.include_router(router=history_router)
    api.include_router(router=wallet_router)
    ctx.include_router(api)    