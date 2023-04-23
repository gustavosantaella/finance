from pydantic import *

class NewWalletDTO(BaseModel):
    members : list= []
    name : str
    owner : str= None
    balance :int = 0
    currency :str