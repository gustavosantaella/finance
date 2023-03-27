from .user_dto import RegisterUserDTO, LoginUserDTO
from .repository import UserRepositoy
from src.modules.auth.v1.auth_dto import RolesDTO
from src.helpers import encrypt, Errors, decrypt, encodeJWT


class UserService:

    def register(payload: RegisterUserDTO):

        payload.password = encrypt(payload.password)
        payload_dict = payload.dict()
        payload_dict['roles'] = [RolesDTO.customer]
        UserRepositoy.new(payload_dict)
        return True

    def login(payload: LoginUserDTO):
        user = UserRepositoy.findByEmail(payload.email)
        if not user:
            return None, Errors.not_found['msg'], Errors.not_found['code']

        if decrypt(payload.password, user.password) != True:
            return None, Errors.invalid_password['msg'], Errors.invalid_password['code']
        token = encodeJWT({
            "userId": str(user.id)
        })
        return token, None, None

    def checkRole(user_id: str, roles: list):
        try:
            hasRoles =  UserRepositoy.check_roles(user_id, roles)
            if len(hasRoles) == 0:
                return False
            return True
        except Exception as e:
            raise e 