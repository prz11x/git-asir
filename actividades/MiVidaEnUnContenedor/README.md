
**Reto1**

- Crear un contenedor docker de ubuntu.  

-`docker run -it --name python-app ubuntu:latest`

- Instalar python, la libreria request y de mysql  

-Actualizamos e instalamos Python y pip

`apt update && apt upgrade -y`

`apt install -y python3 python3-pip`

-Instalamos las librerías requeridas

Normalmente lo instalaríamos con: pip3 install requests mysql-connector-python

En distribuciones recientes de **Debian/Ubuntu** (Python 3.11+), el sistema gestiona los paquetes de Python mediante `apt`. Por ello, **no se recomienda instalar librerías directamente con** `pip3 install` **en el entorno global**, ya que puede romper dependencias del sistema

### Forma recomendada

1. **Usar paquetes oficiales de Debian/Ubuntu** (cuando existen):
    
    bash
    
    ```
    apt update
    apt install -y python3-requests python3-mysql.connector
    ```
    
    - `python3-requests` → instala la librería `requests`.
        
    - `python3-mysql.connector` → instala el conector MySQL para Python.
        
    
    De esta forma, las librerías quedan gestionadas por el sistema y no hay conflictos.
    
2. **Si el paquete no está en los repositorios**, crear un entorno virtual:
    
    bash
    
    ```
    apt install -y python3-venv python3-full
    python3 -m venv /app/venv
    source /app/venv/bin/activate
    pip install requests mysql-connector-python tabulate
    ```
    
    - Esto crea un entorno aislado (`venv`) dentro del contenedor.
        
    - Dentro de ese entorno sí puedes usar `pip install` sin restricciones.

-Verificamos
`python3 -c "import requests, mysql.connector, tabulate; print('Todo OK')"`

-Salimos del contenedor

`exit`

- Crear una imgen personalizada con el contenedor

`docker commit python-ubuntu python-ubuntu-custom`


**Reto 2**

- Crear un contenedor nuevo con la imagen personalizada  de docker
-`docker run -it -v ~/onedrive/desktop/repositorios/git-asir/actividades/MiVidaEnUnContenedor:/app --name python-app python-ubuntu-custom`


Crear una carpeta local en tu máquina

`pablo@Rui MINGW64 ~/onedrive/desktop/repositorios/git-asir/actividades (main)`
`$ mkdir MiVidaEnUnContenedor`

Esta carpeta actuará como punto de unión con el contenedor (lo que guardamos ahí estará disponible en ambos lados).


- Este contenedor tendrá un volumen con una ruta en el disco del anfitrión (Bind)

`docker run -it -v /c/Users/Pablo/OneDrive/Desktop/repositorios/git-asir/actividades/MiVidaEnUnContenedor:/app --name python-app python-ubuntu-custom`

-`-v /c/Users/Pablo/OneDrive/Desktop/repositorios/git-asir/actividades/MiVidaEnUnContenedor:/app` → enlaza tu carpeta local con la carpeta `/app` dentro del contenedor.
    
-Todo lo que pongas en `/app` dentro del contenedor se reflejará en tu sistema anfitrión.

**Reto 3**

- Crear un repositorio git en la carpeta del anfitrión y unirlo con un repositorio en Github

-Como ya tenemos un repositorio donde guardamos las actividades, gracias a el volumen compartido que tenemos, lo que hagamos en el contenedor se vera reflejado en la carpeta indicada. Y en el host desde esa carpeta compartida hacemos los comandos de git que necesitemos (pull, push, commits etc)


-O también podríamos haber montado el volumen con nuestro repositorio de github y así podemos trabajar directamente desde ahí. Primero tendrías que instalar git en el contenedor y ya podrías ejecutar los comandos de git. La carpeta ya esta enlazada a la del host ( que ya estaba conectada a al repositorio de github) por lo que directamente ya podemos trabajar ahi.

-Podemos montar un volumen con la ruta

`docker run -it -v /c/Users/Pablo/OneDrive/Desktop/repositorios/git-asir:/app --name python-app python-ubuntu-custom`

-O montamos los dos volúmenes en el contenedor. Y que en un directorio puedas trabajar en todo lo del repositorio y enotra solo en la carpeta de la actividad

`docker run -it \`
  `-v /c/Users/Pablo/OneDrive/Desktop/repositorios/git-asir/actividades/MiVidaEnUnContenedor:/app/MiVidaEnUnContenedor \`
  `-v /c/Users/Pablo/OneDrive/Desktop/repositorios/git-asir:/app/git-asir \`
  `--name python-app \`
  `python-ubuntu-custom`

-Instalamos git

`apt update && apt upgrade -y`

`apt install git -y`



**Reto 4**

- Crear un contenedor mysql.

`docker run -d \`
  `--name mysql-coches \`
  `-e MYSQL_ROOT_PASSWORD=Abcd1234 \`
  `-p 3307:3306 \`
  `mysql:latest`

Para conectarte a la consola de MySQL:

`docker exec -it mysql-coches mysql -u root -p`


- Crear una base de datos, para almacenar Coches. Los campos seran id, marca, modelo, color, km y precio

create database coches;

USE coches;

CREATE TABLE coches (
  id INT AUTO_INCREMENT PRIMARY KEY,
  marca VARCHAR(50),
  modelo VARCHAR(50),
  color VARCHAR(30),
  km INT,
  precio DECIMAL(10,2)
);

- añadir al menos 10 coches a modo de contenido de muestra.

INSERT INTO coches (marca, modelo, color, km, precio) VALUES
('Toyota', 'Corolla', 'Blanco', 20000, 15000),
('Honda', 'Civic', 'Rojo', 30000, 17000),
('Ford', 'Focus', 'Azul', 25000, 16000),
('Nissan', 'Altima', 'Gris', 18000, 19000),
('Chevrolet', 'Cruze', 'Negro', 40000, 14000),
('Mazda', '3', 'Blanco', 22000, 15500),
('Volkswagen', 'Golf', 'Rojo', 31000, 16500),
('Hyundai', 'Elantra', 'Azul', 27000, 17500),
('Kia', 'Forte', 'Negro', 35000, 16000),
('Peugeot', '308', 'Blanco', 20000, 17200);


**Reto 5** 

- Crear en el repositorio Local un programa en python que se conecte a la base de datos y obtenga los registros de la base de datos.  
    - El programa debe listar los datos de los coches guardados en la base de datos de forma estética.

ID    MARCA          MODELO         COLOR     KM        PRECIO      
------------------------------------------------------------------------  
1     Toyota             Corolla              Blanco    20000     15000       
2     Honda              Civic                Rojo        30000     17000       
3     Ford                  Focus               Azul       25000     16000     


Primero de todo vamos a necesitar una red para que los contenedores puedan verse. Los contenedores por defecto vienen en la misma red bridge, pero la red bridge por defecto no soporta resolución DNS por nombre de contenedor. Solo las redes usuario (creadas con `docker network create`) lo soportan.

Por eso el nombre:

`mysql-coches`

No funciona, pero **la IP sí funcionaría**.

Pero vamos a hacerlo por nombre es lo ideal y por defecto los contenedores no tienen herramientas de red para ver la ip o hacer ping.

Creamos la red y conectamos los contenedores a la red

`docker network create red-coches`

`docker network ls`

`docker network connect red-coches mysql-coches`

`docker network connect red-coches python-app`

y comprobamos 

`docker network inspect red-coches`

`ping mysql-coches`


### Entra al contenedor Python

Si está detenido:

`docker start -ai python-app`

Si está corriendo:

`docker exec -it python-app bash`

---

### Moverse a la carpeta del proyecto

`cd /app cd actividades/MiVidaEnUnContenedor`

---

### Crear el archivo Python

`nano listar_coches.py`

Pega esto:

`import mysql.connector` 
`from tabulate import tabulate`  

`config = {`     
	`'user': 'root',` 
	`'password': 'Abcd1234',`
	`'host': 'mysql-coches',  # nombre del contenedor MySQL` 
	`'database': 'coches'` 
`}` 
`conn = mysql.connector.connect(**config)` 
`cursor = conn.cursor()` 
`cursor.execute("SELECT * FROM coches")` 
`rows = cursor.fetchall()`  
`headers = ["ID", "Marca", "Modelo", "Color", "Kilometraje", "Precio"] print(tabulate(rows, headers, tablefmt="grid"))`  

`cursor.close()` 
`conn.close()`

---



### Prueba el script

Dentro del contenedor:

`python3 listar_coches.py`

**Reto 6**

- Almacenar los datos de conexión a la base de datos en un archivo JSON y que el programa Python los lea de dicho archivo.   Realiza un commit en cada paso

    * crear un nuevo archivo para la modificación. 
    
    -Archivo `config.json` que contiene los datos de conexión a la base de datos:

    `nano config.json`
    {
  "host": "mysql-coches",
  "user": "root",
  "password": "Abcd1234",
  "database": "coches",
  "port": 3306
}

`git add config.json` 
`git commit -m "config.json para conexion a DB"`
`git push`


Modificamos el script de python:

`nano listar_coches.py`

`import json`
`import mysql.connector`
`from tabulate import tabulate`

 `#Leer la configuración desde config.json`
`with open('config.json') as f:`
    `config = json.load(f)`

 `#Conectar a MySQL`
`conn = mysql.connector.connect(**config)`
`cursor = conn.cursor()`

`cursor.execute("SELECT * FROM coches")`
`coches = cursor.fetchall()`

 `#Crear la tabla formateada`
`headers = ["ID", "Marca", "Modelo", "Color", "Kilometraje", "Precio"]`
`print(tabulate(coches, headers=headers, tablefmt="grid"))`

`cursor.close()`
`conn.close()`


- crear el .gitignore para que no suba el archivo con los datos de conexión.
-En la raíz de tu repo, crea o edita `.gitignore`:

config.json
venv/
__pycache__/
*.pyc

`git add .gitignore`
`git commit -m "Añadido .gitignore para config.json y venv"`

**Reto 7**

Formatear la tabla para que quede mas estética con la librería 

+----+--------+---------+-------+-------------+--------+  
| ID | Marca  | Modelo  | Color | Kilometraje  | Precio |  
+----+--------+---------+-------+-------------+--------+  
| 1  | Toyota | Corolla | Rojo  | 25000        | 15000  |  
| 2  | Honda  | Civic   | Azul  | 30000        | 18000  |  
| 3  | Ford   | Focus   | Blanco| 40000        | 17000  |  
+----+--------+---------+-------+-------------+--------+


Instalamos:
`pip install prettytable`


Modificamos el script:
`import mysql.connector`
`from prettytable import PrettyTable`
`import json`

`with open("config.json") as f:`
    `config = json.load(f)`

`conn = mysql.connector.connect(**config)`
`cursor = conn.cursor()`

`cursor.execute("SELECT id, marca, modelo, color, km, precio FROM coches")`
`coches = cursor.fetchall()`

`tabla = PrettyTable()`
`tabla.field_names = ["ID", "Marca", "Modelo", "Color", "Kilometraje", "Precio"]`

`for coche in coches:`
    `tabla.add_row(coche)`

`print(tabla)`

`cursor.close()`
`conn.close()`


**Reto 8**

Crear un contenedor Mongo y conectarse desde la terminal y utilizando MongoDB  

docker run -d \
  --name mongodb \
  -p 27017:27017 \
  -e MONGO_INITDB_ROOT_USERNAME=root \
  -e MONGO_INITDB_ROOT_PASSWORD=Abcd1234 \
  mongo:latest


`docker ps`

`docker exec -it mongodb bash`

`mongosh -u root -p` 



Crea la una bd e inserta en una colección coches con el criterio de de campos del reto anterior  

db.coches.insertMany([
  {
    marca: "Toyota",
    modelo: "Corolla",
    color: "Rojo",
    km: 50000,
    precio: 12000
  },
  {
    marca: "BMW",
    modelo: "M3",
    color: "Negro",
    km: 80000,
    precio: 35000
  }
])

Ver datos:

`db.coches.find().pretty()`


Crear un Script de Python  para leer los datos de colecciones de MongoDB y los imprima en una tabla.


Hacemos que python-app pueda ver el contenedor de mongo:
`$docker network connect red-coches mongodb$`

Entramos en nuestro entorno de python 
`source /app/venv/bin/activate`

y descargamos pymongo:
`pip install pymongo`

`pymongo` **es la librería oficial de Python para trabajar con MongoDB.** En pocas palabras, sirve para que tu código Python pueda **conectarse, consultar y manipular datos en MongoDB** de forma sencilla

en python-app creamos el script:

`nano listar_mongo.py`

Y ponemos dentro:

from pymongo import MongoClient
from prettytable import PrettyTable

 %% Conexión a MongoDB
#"mongodb" es el nombre del contenedor Mongo en la misma red de Docker %%

client = MongoClient("mongodb://root:Abcd1234@mongodb:27017/")

%%  Seleccionar base de datos y colección %%
db = client["cochesdb"]
coleccion = db["coches"]

%% Obtener todos los documentos de la colección %%

coches = list(coleccion.find({}, {"_id": 0}))  
  {"_id": 0} para no mostrar el campo _id  
 
%% Crear tabla con PrettyTable %%
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


%% Mostrar tabla en consola: %%
print(tabla)

%% Cerrar conexión %%
client.close()



**Reto 9**

Subir una imagen personaliza de nuestro contenedor mongodb al  hub de Docker.  

`docker commit mongodb prz11x/mongo-custom:mongo_2.0`

%% mongodb → nombre del contenedor actual
prz11/mongo-custom:1.0 → nombre de la imagen y tag para Docker Hub %%

Iniciamos sesión en dockerhub para subir la imagen:

primero creamos el repositorio en dockerhub donde se va a guardar la imagen

`docker login` 

`docker push prz11x/mongo-custom:mongo_2.0`

En el Linux Parrot montar un contenedor con la imagen subida al hub.

`sudo apt install docker.io -y`

**En Linux Parrot (u otra máquina), descargar la imagen:**
    
`sudo docker pull prz11x/mongo-custom:mongo_2.0`

 **Ejecutar contenedor desde la imagen subida**:
    

`sudo docker run -d --name mongodb-parrot -p 27017:27017 -e MONGO_INITDB_ROOT_USERNAME=root -e MONGO_INITDB_ROOT_PASSWORD=Abcd1234    prz11x/mongo-custom:mongo_2.0`

`sudo docker exec -it mongodb-parrot bash`

`mongosh -u root -p`


**Reto 10  
Pensando.....**

`-----------------` 

`**Evaluación: Verificación en el aula**`
