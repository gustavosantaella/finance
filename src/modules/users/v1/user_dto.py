from pydantic import BaseModel

class RegisterUserDTO(BaseModel):
    email: str
    password: str
    
class LoginUserDTO(RegisterUserDTO):
    pass