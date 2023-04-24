from src.modules.auth.api_controller import router as auth_router
from src.modules.categories.api import router as category_router
from src.modules.financials.history.api import router as history_router
from src.modules.wallet.api import router as wallet_router
from src.modules.users.api import router as users_router
from fastapi import APIRouter

def main(ctx: APIRouter):
    
    @ctx.get('/health')
    def status():
        return {
            "status":True
        }
        
    @ctx.get("/policies-and-privacy")
    def policies_privacy():
        from fastapi import responses
        return responses.HTMLResponse("<!DOCTYPE html> <html> <head> <title>Política de privacidad</title> </head> <body> <h1>Política de privacidad</h1> <p>En nuestra política de privacidad, nos tomamos muy en serio la protección de tus datos personales. Nos comprometemos a cumplir con todas las leyes y regulaciones aplicables en materia de privacidad y protección de datos. Al utilizar nuestros servicios, aceptas los términos de nuestra política de privacidad.</p> <h2>Recopilación de información</h2> <p>No recopilamos información personal identificable a menos que tú decidas proporcionarla voluntariamente. La información que recopilamos puede incluir tu nombre, dirección de correo electrónico, dirección postal, número de teléfono y otra información de contacto. También podemos recopilar información sobre tu uso de nuestros servicios, como la fecha y hora de acceso, la dirección IP, el tipo de navegador y el sistema operativo.</p> <h2>Uso de la información</h2> <p>Utilizamos la información que recopilamos para proporcionarte nuestros servicios y mejorar tu experiencia de usuario. También podemos utilizar la información para fines de marketing y publicidad, siempre y cuando hayas dado tu consentimiento explícito para recibir dichas comunicaciones.</p> <h2>Divulgación de la información</h2> <p>No compartimos tu información personal con terceros sin tu consentimiento explícito, excepto en los casos en que sea necesario para proporcionarte nuestros servicios o cumplir con las leyes y regulaciones aplicables. En caso de que se requiera la divulgación de tu información personal, nos aseguraremos de que se tomen medidas adecuadas para proteger tu privacidad y seguridad.</p> <h2>Seguridad de la información</h2> <p>Utilizamos medidas de seguridad para proteger tus datos personales contra el acceso no autorizado y el uso indebido. Tomamos medidas para garantizar que la información que recopilamos se almacene de manera segura y se proteja contra la pérdida, el robo y la alteración.</p> <h2>Tus derechos</h2> <p>Tienes derecho a acceder, corregir, actualizar y eliminar tu información personal en cualquier momento. También puedes optar por no recibir comunicaciones de marketing y publicidad en cualquier momento.</p> <h2>Cambios en la política de privacidad</h2> <p>Nos reservamos el derecho de modificar esta política de privacidad en cualquier momento. Te recomendamos que revises esta política de privacidad periódicamente para estar al tanto de cualquier cambio.</p> <p>Si tienes alguna pregunta o inquietud sobre nuestra política de privacidad, no dudes en contactarnos.</p> </body> </html>")
    """
        Api version
        """ 
    api = APIRouter(prefix="/api")

    api.include_router(router=auth_router)
    api.include_router(router=category_router)
    api.include_router(router=history_router)
    api.include_router(router=wallet_router)
    api.include_router(router=users_router)
    ctx.include_router(api)    