from .dto import HistoryDTO
from src.modules.categories.service import CategoryService
from .repository import FinancialHistoryRepository
from config.definitions import constants
from fastapi.encoders import jsonable_encoder
import pandas as pd
from datetime import datetime


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
                    item['createdAt'] = str(
                    item['createdAt']) if 'createdAt' in item else None
                    
                item['walletId'] = str(item['walletId'])
                item['categoryId'] = str(item['categoryId'])
                item['categoryName'] = str(item['categories']['name'])
                item['type'] = item['type']
                item['_id'] = str(item['_id'])
                del item['categories']['_id']
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
            
            barChart = []
            categories = []
            
            for i, his in enumerate(history):
                if 'categories' in his:
                    if len(categories) > 0:
                        category_found = list(filter(lambda x: x['category'] == his['categories']['name'] and x['type'] == his['type'], categories))
                        if len(category_found) > 0:
                            category_index = categories.index(category_found[0])
                            categories[category_index]['value'] += his['value']
                        else:
                            categories.append({
                                'category': his['categories']['name'],
                                "type": his['type'],
                                "value": his['value'] if 'value' in his else 0.0
                            })
                    else:
                        categories.append({
                                'category': his['categories']['name'],
                                "type": his['type'],
                                "value": his['value']
                            })
                if 'date' in his:
                    date_name = datetime.strftime(his['date'], '%A') if month else datetime.strftime(his['date'], '%B')
                    found = list(filter(lambda x: x['dateName'] == str(date_name), barChart))
                    if len(found) > 0:
                        index =  barChart.index(found[0])
                        if his['type'] in barChart[index]:
                            barChart[index][his['type']] += his['value'] 
                        else:
                            barChart[index][his['type']] = his['value']
                        
                    else:
                        date_converted = datetime.strftime(his['date'], '%Y-%m-%d')
                        barChart.append({
                            "date": str(date_converted),
                            "dateName": str(date_name),
                            
                                "income":0.9,
                                "expense":0.0
                            
                        })
                    del his['date']
            
            df = pd.DataFrame(barChart)
            df_categories = pd.DataFrame(categories)
            category_expenses = []
            category_incomes = []
            if len(categories) > 0:
                category_incomes = df_categories.where(df_categories['type'] == 'income').reset_index()
                category_expenses = df_categories.where(df_categories['type'] == 'expense').reset_index()
                category_expenses['value']  = round((category_expenses['value'] / totalHistory) * 100, 3)
                category_incomes['value']  = round((category_incomes['value'] / totalHistory) * 100, 3)
                category_incomes = category_incomes.dropna().to_dict('records')
                category_expenses = category_expenses.dropna().to_dict('records')
            # df = df.sort_values('index', ascending=True).reset_index()
            # for d in history: d   el d['date'] 

            if month and 'date' in df:
                if month:
                    df['index'] = pd.to_datetime(df['date']).dt.day_of_week
                else:
                    df['index'] = pd.to_datetime(df['date']).dt.month
                
                df = df.sort_values('index', ascending=True).reset_index()

                    
            # df["index"] = df["barchart"].map(lambda x: x["index"])

            # # Ordenar el DataFrame por el campo "index"
            # df = df.sort_values("index").reset_index()
            return {
                "metrics": {
                    "incomes":incomes_percentage,
                    "expenses": expenses_percentage,
                    'barchart': df.to_dict('records'),
                    "piechart": {
                        'incomes': category_incomes,
                        'expenses': category_expenses,
                    }
                },
                "incomes":incomes if incomes> 0 else 0.0,
                "expenses":expenses if expenses> 0 else 0.0,
                "total":totalHistory if totalHistory> 0 else 0.0,
                "history": history
            }
        except Exception as e:
            print(e)
            raise e
        
    def historyDetail(walletId, historyId):
        try:
            data = list(FinancialHistoryRepository.detail(walletId, historyId))
            if len(data) == 0:
                raise Exception("Not found")
            data = data[0]
            del data['categoryId']
            return data
            
        except Exception as e:
            raise e
