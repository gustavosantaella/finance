from .dto import HistoryDTO
from src.modules.categories.service import CategoryService
from .repository import FinancialHistoryRepository
from config.definitions import constants


class FinancialHistoryService:

    def add(body: HistoryDTO):
        try:
            category = CategoryService.finOneByIdOrNameOrOther(body.categoryId)

            body.categoryId = category.id

            body = body.dict()
            body['gateway'] = {}

            body['gateway']['provider'] = body['provider']

            del body['provider']

            FinancialHistoryRepository.new(body)

            return True
        except Exception as e:
            raise e

    def getHistoryByWalletId(id: str, month=None, date=None):
        try:
            histoires = list(
                FinancialHistoryRepository.getByWalletId(id, month, date))

            def format(item):
                if 'date' in item:
                    del item['date']
                item['createdAt'] = str(
                    item['createdAt']) if 'createdAt' in item else None
                item['walletId'] = str(item['walletId'])
                item['categoryId'] = str(item['categoryId'])
                item['type'] = item['type']
                item['_id'] = str(item['_id'])
                return item

            history = list(map(format, histoires))
            
            get_sum_by_type = lambda history_type: lambda x: x['value'] if x['type'] == constants['wallet_history']['types'][history_type] else 0
            incomes = sum(list(map(
               get_sum_by_type(constants['wallet_history']['types']['income']), history)))
            expenses = sum(list(map(get_sum_by_type(constants['wallet_history']['types']['expense'])
                 , history)))
            totalHistory = sum(list(map(lambda x: x['value'], history)))
            incomes_percentage = round((incomes / totalHistory) * 100, 3) if totalHistory > 0 else 0.0
            expenses_percentage = round((expenses / totalHistory) * 100, 3) if totalHistory > 0 else 0.0 

            return {
                "metrics": {
                    "incomes":incomes_percentage,
                    "expenses": expenses_percentage,

                },
                "incomes":incomes if incomes> 0 else 0.0,
                "expenses":expenses if expenses> 0 else 0.0,
                "total":totalHistory if totalHistory> 0 else 0.0,
                "history": history
            }
        except Exception as e:
            raise e
