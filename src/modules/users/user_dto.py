from pydantic import BaseModel

class RegisterUserDTO(BaseModel):
    email: str
    password: str
    country: str
    
class LoginUserDTO(RegisterUserDTO):
    country: None = None