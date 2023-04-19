import jwt
import bcrypt
from fastapi.responses import JSONResponse
from config import env
from config.definitions import constants

def encodeJWT(entry: dict) -> str:
    return jwt.encode(entry, env("JWT_SECRET_KEY"), algorithm="HS256")


def decodeJWT(entry: str):
    return jwt.decode(entry, env("JWT_SECRET_KEY"), algorithms=["HS256"])


def encrypt(entry: str) -> str:
    encrypted = bcrypt.hashpw(entry.encode(), bcrypt.gensalt())
    return encrypted.decode("utf-8")


def decrypt(entry: str, hashed: str) -> bool:
    if bcrypt.checkpw(entry.encode(), hashed.encode()):
        return True
    return False


"""
Summary: Http json response

Attributes;
----------
content: any
    body of response
    
status: int
    by default is OK(200)
    
**kwargs: dict
    another parameters
    
"""


def response(content = None, status: int = 200, **kwargs):
    result = {}
    result['status'] = status if status != None else 200
    result['ok'] = constants['responses']['ok'] if 'ok' not in kwargs and status not in Errors.codes  else constants['responses']['err'] 
    if "error" in kwargs and kwargs['error'] != None:
        result['error'] = kwargs['error']
        result['status'] = 400

    if content != None:
        result['data'] = content
        
    if 'extra' not in kwargs:
        kwargs['extra'] = {}
        

    return JSONResponse(content=result, status_code=result['status'], **kwargs['extra'])


class Errors:

    codes = [400, 403, 401, 500]
    not_found = {
        "msg": "User not found",
        "code": 404
    }

    invalid_password = {
        "msg": 'Invalid password',
        "code": 400
    }

from enum import Enum
class EnumHelper(Enum): 
    
    @classmethod
    def list(cls):
        return list(map(lambda c: c.value, cls))
