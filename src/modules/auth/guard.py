from functools import wraps
from src.helpers import response, decodeJWT
from fastapi import Request
from src.modules.users.service import UserService
def AuthRole(roles):
    def decorator(fn):
        @wraps(fn)
        def decorated_function(request: Request, *args, **kwargs):
            request.profile = {}
            token :str = request.headers.get('Authorization')
            if not token:
               return response(error='Unauthorized', status=403) 
            
            token = token.split(" ")
            
            if len(token) != 2:
                return response(error="Invalid token", status=400)
            
            if "Wafi" not in token:
                return response(error="Invalid token", status=400)
            
            decode = decodeJWT(token[1])
            request.profile['userId'] = decode['userId']
            hasRoles = UserService.checkRole(decode['userId'], roles)
            if hasRoles != True:
                return response(error="Unauthorized for this operation", status=403)
            return fn(request, *args, **kwargs)
        return decorated_function
    return decorator
    