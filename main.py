from fastapi import FastAPI, APIRouter
from src.controllers.api.auth.AuthController import router as auth_router
from database.mongo.main import connect_db


# cretae app
app = FastAPI()

"""
    Api version 1.0
    """ 
apiv1 = APIRouter(prefix="/api/v1")

apiv1.include_router(router=auth_router)
apiv1.include_router(router=auth_router)
app.include_router(apiv1)

connect_db()

