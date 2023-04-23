from pydantic import BaseModel
from typing import Union
class RegisterUserDTO(BaseModel):
    email: str
    password: str
    country: str
    
class LoginUserDTO(RegisterUserDTO):
    country: None = None
    
class UpdateUserDTO(RegisterUserDTO):
    country: None = None
    name: Union[str, None] = None
    password: Union[str, None] = None
    email: Union[str, None] = None