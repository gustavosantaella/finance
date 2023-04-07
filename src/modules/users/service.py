from .user_dto import RegisterUserDTO, LoginUserDTO
from .repository import UserRepositoy
from src.modules.auth.auth_dto import RolesDTO
from src.helpers import encrypt, Errors, decrypt, encodeJWT
from src.modules.countries.service import CountryService
from src.modules.wallet.service import WalletService
from src.modules.wallet.dto import NewWalletDTO
from src.modules.wallet.service import WalletService
from datetime import datetime


class UserService:

    def register(payload: RegisterUserDTO):
        try:
                
            payload.password = encrypt(payload.password)
            payload_dict = payload.dict()
            payload_dict['roles'] = [RolesDTO.customer]
            country = CountryService.findByIso2(payload.country)
            payload_dict['country'] = country.iso2
            user = UserRepositoy.new(payload_dict)
            wallet = NewWalletDTO(
                name=str(datetime.timestamp(datetime.now())),
                owner=str(user.id),
                currency= country.currency
                )
            WalletService.new(wallet)
            return True
        except Exception as e:
            raise e

    def login(payload: LoginUserDTO):
        user = UserRepositoy.findByEmail(payload.email)
        if not user:
            return None, Errors.not_found['msg'], Errors.not_found['code']

        if decrypt(payload.password, user.password) != True:
            return None, Errors.invalid_password['msg'], Errors.invalid_password['code']
        token = encodeJWT({
            "userId": str(user.id)
        })
        wallets = WalletService.getAllByOwnerId(str(user.id))
        print('wallets',wallets)
        return {
            "token": token,
            "userId": str(user.id),
            "wallets": wallets
            }, None, None

    def checkRole(user_id: str, roles: list):
        try:
            hasRoles =  UserRepositoy.check_roles(user_id, roles)
            if len(hasRoles) == 0:
                return False
            return True
        except Exception as e:
            raise e 