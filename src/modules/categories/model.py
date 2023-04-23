from mongoengine import *

class CategoryModel(Document):
    name = StringField(required=True)
    
    meta = {
        "collection":"categories"
    }
    
    def all():
        return CategoryModel.objects.all().values_list("name", 'id')
    
    def findByIdOrName(id: str):
        return CategoryModel.objects.get(id=id)
    
    def findOthers():
        return CategoryModel.objects(name='OTHERS').first()
        