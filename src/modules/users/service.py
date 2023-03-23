from .user_dto import RegisterUserDTO
from .repository import UserRepositoy
class UserService:
        
    def register(payload: RegisterUserDTO):
        print(UserRepositoy.new(payload.dict()))