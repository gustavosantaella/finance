from mongoengine import *
class CountryRepository(DynamicDocument):
    iso2 = StringField(required=True, unique=True, upper=True) 
    name = StringField(required=True, unique=True) 
    
    meta = {
        "collection":"countries"
    }
    
    
    def findByIso2(iso2: str):
        return CountryRepository.objects(iso2=iso2).first()