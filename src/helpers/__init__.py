import jwt
import bcrypt
from fastapi.responses import JSONResponse


def encodeJWT(entry: dict) -> str:
    return jwt.encode(entry, "secret", algorithm="HS256")


def decodeJWT(entry: str):
    return jwt.decode(entry, "secret", algorithms=["HS256"])


def encrypt(entry: str) -> str:
    encrypted = bcrypt.hashpw(entry.encode(), bcrypt.gensalt())
    return encrypted


def decrypt(entry: str, hashed: str) -> bool:
    if bcrypt.checkpw(entry.encode(), hashed):
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


def response(content: any = {}, status: int = 200, **kwargs):
    result = {}
    result['status'] = status if status != None else 200
    if "error" in kwargs and kwargs['error'] != None:
        result['error'] = kwargs['error']

    if content != None:
        result['data'] = content

    print(result)
    return JSONResponse(content=result, status_code=result['status'])


class Errors:

    not_found = {
        "msg": "User not found",
        "code": 404
    }

    invalid_password = {
        "msg": 'Invalid password',
        "code": 400
    }
