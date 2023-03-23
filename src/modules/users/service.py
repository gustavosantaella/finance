from .user_dto import RegisterUserDTO, LoginUserDTO
from .repository import UserRepositoy
from src.helpers import encrypt, Errors, decrypt
class UserService:
        
    def register(payload: RegisterUserDTO):
        
        payload.password = encrypt(payload.password)
        payload_dict = payload.dict()
        UserRepositoy.new(payload_dict)
        return True
    
    def login(payload: LoginUserDTO):
        user = UserRepositoy.findByEmail(payload.email)
        if not user:
            return None, Errors.not_found['msg'], Errors.not_found['code']
        
        if decrypt(payload.password, user.password) != True:
            return None, Errors.invalid_password['msg'], Errors.invalid_password['code']
        return True, None, None