from .user_dto import RegisterUserDTO, LoginUserDTO
from .repository import UserRepositoy
from src.modules.auth.auth_dto import RolesDTO
from src.helpers import encrypt, Errors, decrypt, encodeJWT
from src.modules.countries.service import CountryService
from src.modules.wallet.service import WalletService
from src.modules.wallet.dto import NewWalletDTO
from src.modules.wallet.service import WalletService
from src.helpers import to_json
from datetime import datetime
from .user_dto import UpdateUserDTO
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
                currency= country.currency,
                walletId=datetime.timestamp(datetime.now())
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
    
    def get_by_id(id: str):
        try:
            data = UserRepositoy.findById(id)
            
            return to_json(data.to_json())
        except Exception as e:
            raise e
    
    def update_info(payload: UpdateUserDTO, user_id: str):
        try:
            data = payload.dict()
            aux_data = {}
            for k in data:
                if data[k] != None:
                    aux_data[k] = data[k]
            
            del data
            
            if 'password' in aux_data:
                aux_data['password'] = encrypt(aux_data['password'])

            UserRepositoy.updateInfo(aux_data, user_id)
            return True
        except Exception as e:
            raise e
        
