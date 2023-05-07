from .dto import NewWalletDTO
from .repository import WalletRepositroy

class WalletService:
    
    def new(payload: NewWalletDTO):
        try:
            return WalletRepositroy.new(payload.dict())
        except Exception as e:
            raise e
        
    def getAllByOwnerId(ownerId: str):
        try:
            wallets = list(WalletRepositroy.byOwner(ownerId))
            if len(wallets) == 0:
                raise Exception("You haven't wallets")
            
            return wallets
        except Exception as e:
            raise e
        
    
    
    def balance(walletId, dates):
        try:
            from_date, to_date = dates
            wallet = list(WalletRepositroy.find(walletId, dates))
            if len(wallet) == 0:
                raise Exception("Wallet not found")
            def sum_values(item):
                return item['value']
            
            wallet = wallet[0]
            incomes = sum(list(map(sum_values, list(filter(lambda x: x if x['type'] == 'income' else [], wallet['history'])) )))    
            expenses = sum(list(map(sum_values, list(filter(lambda x: x if x['type'] == 'expense' else [], wallet['history'])) )))    
            balance = incomes - expenses
            growth_rate_value = 0
            return {
                "balance": round(balance, 2),
                "incomes": round(incomes,2),
                "expenses": round(expenses,2),
                "growthRate": round(growth_rate_value,2),
                "info": {
                    "walletId": str(wallet['_id']),
                    "owner": str(wallet['owner']),
                    "currency": str(wallet['currency']),
                    "createAt": str(wallet['createAt']),
                }
            }
        except Exception as e:
            raise e