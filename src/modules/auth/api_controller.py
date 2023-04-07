from fastapi import APIRouter, Request
from src.modules.users.user_dto import RegisterUserDTO, LoginUserDTO
from src.modules.users.service import UserService
from .auth_dto import RolesDTO
from src.helpers import response
from .guard import AuthRole

router = APIRouter(prefix='/auth')

@router.post("/register")
def register(request: Request, body: RegisterUserDTO):
    try:
        res = UserService.register(body)
        return response(content=res)
    except Exception as e:
        return response(error=str(e), status=400)
    
@router.post("/login")
def register(body: LoginUserDTO):
    try:
        res, err, status = UserService.login(body)
        return response(content=res, status=status, error=err)
    except Exception as e:
        return response(status=400, error=str(e))