from fastapi import APIRouter
from src.modules.users.user_dto import RegisterUserDTO, LoginUserDTO
from src.modules.users.service import UserService
from src.helpers import response
router = APIRouter(prefix='/auth')

@router.post("/register")
def register(body: RegisterUserDTO):
    res = UserService.register(body)
    return response(content=res)

@router.post("/login")
def register(body: LoginUserDTO):
    try:
        res, err, status = UserService.login(body)
        return response(content=res, status=status, error=err)
    except Exception as e:
        return response(status=400, error=str(e))