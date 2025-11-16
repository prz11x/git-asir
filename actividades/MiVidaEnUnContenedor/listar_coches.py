import mysql.connector 
from tabulate import tabulate  

config = {     
	'user': 'root', 
	'password': 'Abcd1234',
	'host': 'mysql-coches',  # nombre del contenedor MySQL 
	'database': 'coches' 
} 
conn = mysql.connector.connect(**config) 
cursor = conn.cursor() 
cursor.execute("SELECT * FROM coches") 
rows = cursor.fetchall()  
headers = ["ID", "Marca", "Modelo", "Color", "Kilometraje", "Precio"]
print(tabulate(rows, headers, tablefmt="grid"))  

cursor.close() 
conn.close()
