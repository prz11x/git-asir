import json
import mysql.connector
from tabulate import tabulate

# Leer la configuración desde config.json
with open('config.json') as f:
    config = json.load(f)

# Conectar a MySQL
conn = mysql.connector.connect(**config)
cursor = conn.cursor()

cursor.execute("SELECT * FROM coches")
coches = cursor.fetchall()

# Crear la tabla formateada
headers = ["ID", "Marca", "Modelo", "Color", "Kilometraje", "Precio"]
print(tabulate(coches, headers=headers, tablefmt="grid"))

cursor.close()
conn.close()
