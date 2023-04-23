from os import getenv
def env(key:str):
    environment = getenv(key.upper())
    if not environment:
        raise Exception(f"{key} Environment not exists")
    
    return environment