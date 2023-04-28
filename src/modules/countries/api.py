from fastapi import APIRouter
from .service import CountryService
from src.helpers import response

router = APIRouter(prefix='/countries')

@router.get("")
def get_countries_keys():
    try:
        data = CountryService.get_keys()
        data = list(map (lambda x: x['name'], data))
        return response(content=data)
    except Exception as e:
        return response(error=str(e))