from fastapi import APIRouter
from src.modules.users.user_dto import RegisterUserDTO
from src.modules.users.service import UserService
router = APIRouter(prefix='/auth')

@router.post("/register")
def register(body: RegisterUserDTO):
    UserService.register(body)
    return {
        "as":2
    }