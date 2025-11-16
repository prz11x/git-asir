import mysql.connector
from prettytable import PrettyTable
import json

# Cargar datos de conexión desde config.json
with open("config.json") as f:
    config = json.load(f)

# Conectar a MySQL
conn = mysql.connector.connect(**config)
cursor = conn.cursor()

# Ejecutar consulta
cursor.execute("SELECT id, marca, modelo, color, km, precio FROM coches")
coches = cursor.fetchall()

# Crear tabla con PrettyTable
tabla = PrettyTable()
tabla.field_names = ["ID", "Marca", "Modelo", "Color", "Kilometraje", "Precio"]

for coche in coches:
    tabla.add_row(coche)

# Mostrar tabla
print(tabla)

# Cerrar conexión
cursor.close()
conn.close()
