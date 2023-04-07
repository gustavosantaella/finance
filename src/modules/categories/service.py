from .model import CategoryModel
class CategoryService:
    
    def newByAdmin():
        try:
            CategoryService.newByAdmin()
        except Exception as e:
            raise e
        
    def all():
        try:
            data = CategoryModel.all()
            return list(map(lambda x: {"label":x[0], "id":str(x[1])}, data)) , None
        except Exception as e:
            raise e
        
    def finOneByIdOrNameOrOther(id: str = None):
        try:
            category = CategoryModel.findOthers()
            if id:
                category = CategoryModel.findByIdOrName(id)
                print(category.name)
                if not category:
                    print(id)
                    raise Exception("Category not found")
            return category
        except Exception as e:
            raise e