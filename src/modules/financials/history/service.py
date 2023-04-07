from .dto import HistoryDTO
from src.modules.categories.service import CategoryService
from .repository import FinancialHistoryRepository
import __main__ as a
class FinancialHistoryService: 
    
    def add(body: HistoryDTO):
        try:
            category = CategoryService.finOneByIdOrNameOrOther(body.categoryId)
            
            body.categoryId = category.id
            
            body = body.dict()
            body['gateway'] = {}
            
            body['gateway']['provider'] =  body['provider']
            
            del body['provider']
        
            FinancialHistoryRepository.new(body)
            
            return True
        except Exception as e:
            raise e
        
    def getHistoryByWalletId(id: str):
        try:
            histoires = list(FinancialHistoryRepository.getByWalletId(id))
            
            def format(item):
                item = item.to_mongo()
                item['walletId'] = str(item['walletId'])
                item['categoryId'] = str(item['categoryId'])
                item['_id'] = str(item['_id'])
                return item
            
            return list(map(format, histoires))
        except Exception as e:
            raise e