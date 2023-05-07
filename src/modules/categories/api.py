from fastapi import APIRouter
from src.helpers import response
from .service import CategoryService
from src.modules.auth.guard import AuthRole
from src.modules.auth.auth_dto import RolesDTO
from fastapi import Request

router = APIRouter(prefix="/categories")

@router.post('/admin')
def categoriesByAdmin():
    try:
        data, error = CategoryService.newByAdmin()
        return response(contnet=[1,2,3], status=200)
    except Exception as e:
        return response(error=str(e), status=400)
    
@router.get('/')
@AuthRole(['customer'])
def all(request: Request, lang: str = 'en'):
    try:
        data, error = CategoryService.all(lang)
        return response(data)
    except Exception as e:
        return response(error=str(e), status=400)