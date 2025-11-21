pablo2@uss:/$ `sudo find / -iname "*A43*"`

/opt/enterprise/mensaje_starfleet_A43

`cat mensaje_starfleet_A43`
`El codigo de la siguiente mision es:`
`MD_4353.mis`

`sudo find / -iname "*4353*"`
/etc/MD_4353.mis

`cat MD_4353.mis`

Activación del Módulo de Simulación — Docker

La Flota trabaja con contenedores para pruebas en espacio profundo.

Debes:

Instalar Docker

sudo apt update
sudo apt install docker.io -y
sudo systemctl enable --now docker
sudo usermod -aG docker $USER

docker ps


Levantar contenedores básicos (Apache, MariaDB, Alpine…)

docker run -d --name apache-test -p 8080:80 httpd

docker run -d --name mariadb-test \
  -e MYSQL_ROOT_PASSWORD=Abcd1234 \
  -e MYSQL_DATABASE=flota 
  mariadb

docker run -it --name alp-test alpine sh (solo tiene alpine este contenedor, no tiene nginx)


Desplegar un WordPress que funcionará como si fuera un panel de registro de misión
(El sistema esta lleno de secretos)

 `sudo find / -iname "secreto"`
 /home/secreto

`cd /home/secreto/`

`ls`
wp

`cd wp`

pablo2@uss:/home/secreto/wp$ `ls`
bd  web

`ls bd`

dokerfile.db  README.ME

`cat bd/dokerfile.db`

FROM mariadb:latest

LABEL maintainer="Tu Nombre <tuemail@ejemplo.com>"
LABEL description="Contenedor MariaDB para WordPress en entorno de prácticas"

ENV MYSQL_ROOT_PASSWORD=rootpass
ENV MYSQL_DATABASE=wordpress
ENV MYSQL_USER=wpuser
ENV MYSQL_PASSWORD=wppass

VOLUME ["/var/lib/mysql"]

EXPOSE 3306



`cat bd/README.ME`
Para construir
docker build -f dokerfile.db -t mi-mariadb-wp:1.0 .


Ejecutar el contenedor con esa imagen:

docker network create wp-net

docker run -d \
  --name wp-db \
  --network wp-net \
  mi-mariadb-wp:1.0

`ls web`
dokerfile.wp  readme.me

 `cat dokerfile.wp`

**FROM wordpress:latest**

**LABEL maintainer="Tu Nombre <tuemail@ejemplo.com>"**
**LABEL description="Contenedor WordPress para entorno de prácticas"**

**COPY custom-index.php /var/www/html/index.php**

**EXPOSE 80**


`cat web/readme.me`
`#Construir`
`docker build -f dokerfile.wp -t mi-wordpress:1.0 .`

`#Ejecutar el contenedor usando la BD anterior:`

docker run -d \
  --name wp \
  --network wp-net \
  -e WORDPRESS_DB_HOST=wp-db:3306 \
  -e WORDPRESS_DB_USER=wpuser \
  -e WORDPRESS_DB_PASSWORD=wppass \
  -e WORDPRESS_DB_NAME=wordpress \
  -p 8080:80 \
  -v wp-html:/var/www/html \
  mi-wordpress:1.0

http://192.168.1.60:8080

%% Creamos una red interna para WordPress:

docker network create wp-net

Crear base de datos MariaDB:

docker run -d --name wp-db --network wp-net \
  -e MYSQL_ROOT_PASSWORD=Abcd1234 \
  -e MYSQL_DATABASE=wordpress \
  mariadb

Levantar WordPress

docker run -d --name wp --network wp-net \
  -p 8081:80 \
  -e WORDPRESS_DB_HOST=wp-db \
  -e WORDPRESS_DB_USER=root \
  -e WORDPRESS_DB_PASSWORD=Abcd1234 \
  -e WORDPRESS_DB_NAME=wordpress \
  wordpress
car
%%


Crear un contenedor personalizado (puede ser un simple servidor web con un HTML estilo Starfleet)

En nuestro servidor:

mkdir starfleet-web
cd starfleet-web

nano index.html
`<!DOCTYPE html>`
`<html>`
`<head>`
`<meta charset="UTF-8">`
`<title>USS Competencial — Módulo de Ingeniería</title>`
`<style>`
`body {`
    `background:black;`
    `color:#33FF99;`
    `font-family:monospace;`
    `padding:40px;`
`}`
`h1 { color:#FF9933; }`
`</style>`
`</head>`
`<body>`
`<h1>USS Competencial — Módulo Web LCARS</h1>`
`<p>Servidor operativo. Sistemas de ingeniería funcionando dentro de parámetros.</p>`
`</body>`
`</html>`


Luego debes construir tu propia imagen con Dockerfile y subirla a:

👉 Docker Hub — como si fuera un “Módulo de Ingeniería aprobado por Starfleet”.

Creamos Dockerfile:

`nano Dockerfile`

`FROM nginx:alpine`
`COPY index.html /usr/share/nginx/html/index.html`

Construimos la imagen:

docker build -t starfleet-web:v1 .

docker run -d -p 7777:80 --name mod-simulacion starfleet-web:v1

http://192.168.1.60:7777


Subir la imagen a Docker Hub:

docker login -u prz11x

docker tag starfleet-web:v1 prz11x/starfleet-web:v1

docker push prz11x/starfleet-web:v1



---------------------------------
MENSAJE DE LA FLOTA ESTELAR:
Nos han enviado un estraño archivo con la extensión sh.
mision_oculta.sh




