
## Parte 1 — WordPress con Docker

#### 1.1 Verificar Docker instalado


docker --version
docker ps

Si `docker ps` da error de permisos, añade tu usuario al grupo `docker`:


sudo usermod -aG docker $USER
newgrp docker
docker ps

### 2. Crear la red para comunicar contenedores

Creamos una red llamada `wp-net`. Esto permite que WordPress encuentre a MySQL por el nombre del contenedor.

docker network create wp-net
docker network ls

### 3. Crear volúmenes para persistencia

Necesitamos guardar:

-Datos de MySQL (bases de datos).
 
-Archivos de WordPress (plugins, temas, subidas).


docker volume create wp-db
docker volume create wp-html
docker volume ls

### 4. Levantar la base de datos (MySQL)

#### 4.1 Ejecutar MySQL con variables de entorno

Creamos el contenedor `wp-mysql`:


docker run -d \
  --name wp-mysql \
  --network wp-net \
  -v wp-db:/var/lib/mysql \
  -e MYSQL_DATABASE=wordpress \
  -e MYSQL_USER=wpuser \
  -e MYSQL_PASSWORD=wp-pass-123 \
  -e MYSQL_ROOT_PASSWORD=root-pass-123 \
  mysql:8.0

#### 4.2 Comprobar que está vivo


docker ps
docker logs wp-mysql --tail 30


### 5. Levantar WordPress

#### 5.1 Ejecutar WordPress apuntando a la DB

Creamos el contenedor `wp-web` y lo publicamos en el puerto `8080`:


docker run -d \
  --name wp-web \
  --network wp-net \
  -p 8080:80 \
  -v wp-html:/var/www/html \
  -e WORDPRESS_DB_HOST=wp-mysql:3306 \
  -e WORDPRESS_DB_NAME=wordpress \
  -e WORDPRESS_DB_USER=wpuser \
  -e WORDPRESS_DB_PASSWORD=wp-pass-123 \
  wordpress:latest

#### 5.2 Verificar logs


docker logs wp-web --tail 30

### 6. Acceso por navegador y asistente de instalación

Abre en tu navegador: [http://localhost:8080](http://localhost:8080/)

Completa el instalador de WordPress:

### 7. Verificación técnica 

#### 7.1 Ver la red y quién está conectado


docker network inspect wp-net

#### 7.2 Ver volúmenes y dónde se usan


docker inspect wp-mysql | grep -i mount -n
docker inspect wp-web | grep -i mount -n

### 8. Parar, arrancar y reiniciar (operaciones típicas)

#### 8.1 Parar todo


docker stop wp-web wp-mysql

#### 8.2 Arrancar todo


docker start wp-mysql wp-web

#### 8.3 Reiniciar WordPress


docker restart wp-web

### 9. Limpieza (sin perder datos)

Si borras contenedores, los datos siguen porque están en volúmenes.

#### 9.1 Borrar contenedores


docker rm -f wp-web wp-mysql

#### 9.2 Volver a crearlos usando los mismos volúmenes

Repite los comandos `docker run` de los pasos **4.1** y **5.1**. Verás que todo sigue funcionando con los mismos datos.

### 10. Limpieza total (borrado completo)

⚠️ **Esto elimina la web y la base de datos de forma permanente.**


docker rm -f wp-web wp-mysql 2>/dev/null
docker volume rm wp-db wp-html 2>/dev/null
docker network rm wp-net 2>/dev/null

## Parte 2 — Comandos WPScan

sudo apt update
sudo apt install wpscan

### 1. Identificación básica del objetivo


wpscan --url http://localhost:8080


    



### 2. Enumeración de usuarios


wpscan --url http://localhost:8080 --enumerate u

### 3. Enumeración de plugins vulnerables

wpscan --url http://localhost:8080 --enumerate p

### 4. Enumeración de temas


wpscan --url http://localhost:8080 --enumerate t

### 5. Uso con API token (si tienes)

Regístrate en [wpscan.com](https://wpscan.com/register) y obtener un token gratuito.


wpscan --url http://localhost:8080 --api-token


### 6. Detección de configuraciones inseguras (ataques pasivos)

wpscan --url http://localhost:8080 --enumerate vp,vt,tt,cb,dbe,u,m --api-token El_TOKEN