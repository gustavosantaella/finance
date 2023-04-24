from fastapi import APIRouter, Request
from src.modules.auth.guard import AuthRole
from src.helpers import response
from .service import UserService
from .user_dto import UpdateUserDTO

router = APIRouter(prefix='/users')

@router.put('/info')
@AuthRole(['customer'])
def update_user(request: Request, body: UpdateUserDTO):
    try:
        data = UserService.update_info(body, request.profile['userId'])
        return response(content=data)
    except Exception as e:
        return response(error=str(e))
    
    

@router.get("/info")
@AuthRole(['customer'])
def get_user(request: Request):
    try:
        data = UserService.get_by_id(request.profile['userId'])
        return response(content=data)
    except Exception as e:
        return response(error=str(e))
 
@router.get("/delete-account")
@AuthRole(['customer'])
def get_user(request: Request):
    try:
        data = UserService.delete_account(request.profile['userId'])
        return response(content=data)
    except Exception as e:
        return response(error=str(e))
    
# @router.get("/get")
# @AuthRole(['customer'])
# def get_user(request: Request):
#     try:
#         data = UserService.get_by_id(request.profile['userId'])
#         return response(content=data)
#     except Exception as e:
#         return response(error=str(e))