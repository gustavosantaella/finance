from mongoengine import *
class CountryRepository(DynamicDocument):
    iso2 = StringField(required=True, unique=True, upper=True) 
    name = StringField(required=True, unique=True) 
    
    meta = {
        "collection":"countries"
    }
    
    
    def findByIso2(iso2: str):
        return CountryRepository.objects(iso2=iso2).first()
    
    
    def findByName(name: str):
        return CountryRepository.objects(name=name).first()
    
    
    def get_keys() -> list:
        return list(CountryRepository.objects().aggregate([
            {
              "$sort":{"name":1}  
            },
            {
                "$project":{
                    "name": 1,
                    "_id": 0
                }
            }
        ]))