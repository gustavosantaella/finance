from src.modules.auth.v1.api_controller import router as auth_router
from fastapi import APIRouter

def main(ctx: APIRouter):
    
    @ctx.get('/health')
    def status():
        return {
            "status":True
        }
    """
        Api version 1.0
        """ 
    apiv1 = APIRouter(prefix="/api/v1")

    apiv1.include_router(router=auth_router)
    apiv1.include_router(router=auth_router)
    ctx.include_router(apiv1)    