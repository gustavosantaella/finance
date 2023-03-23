import jwt


def encodeJWT(entry: dict):
    return jwt.encode(entry, "secret", algorithm="HS256")

def decodeJWT(entry: str):
    return jwt.decode(entry, "secret", algorithms=["HS256"])