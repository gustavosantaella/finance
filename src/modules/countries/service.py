from .repository import CountryRepository
class CountryService:
    
    def findByIso2(iso2: str):
        try:
            country = CountryRepository.findByIso2(iso2)
            if not country:
                raise Exception("Country not found")
            return country
        except Exception as e:
            raise e