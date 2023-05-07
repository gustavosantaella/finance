from mongoengine import *

class CategoryModel(Document):
    name = StringField(required=True)
    lang = StringField(required=True)
    
    meta = {
        "collection":"categories"
    }
    
    def all(lang='en'):
        return CategoryModel.objects(lang=lang).values_list("name", 'id').order_by("name")
    
    def findByIdOrName(id: str):
        return CategoryModel.objects.get(id=id)
    
    def findOthers():
        return CategoryModel.objects(name='OTHERS').first()
        