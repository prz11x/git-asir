from pymongo import MongoClient
from prettytable import PrettyTable

# Conexión a MongoDB
# "mongodb" es el nombre del contenedor Mongo en la misma red de Docker
client = MongoClient("mongodb://root:Abcd1234@mongodb:27017/")

# Seleccionar base de datos y colección
db = client["cochesdb"]
coleccion = db["coches"]

# Obtener todos los documentos de la colección
coches = list(coleccion.find({}, {"_id": 0}))   

# Crear tabla con PrettyTable
tabla = PrettyTable()
tabla.field_names = ["Marca", "Modelo", "Color", "Kilómetros", "Precio"]

for coche in coches:
    tabla.add_row([
        coche["marca"],
        coche["modelo"],
        coche["color"],
        coche["km"],
        coche["precio"]
    ])

# Mostrar tabla en consola
print(tabla)

# Cerrar conexión
client.close()
