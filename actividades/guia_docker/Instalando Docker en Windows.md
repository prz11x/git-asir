
Descargar imagenes: 

Ejemplos:  docker pull ubuntu docker pull ubuntu:20.04 

Crear y ejecutar contenedores docker run -it ubuntu docker run -it --name mi-ubuntu ubuntu docker run --name mi-ubuntu -it -v /datos-persistentes ubuntu docker run --name mi-ubuntu -it -v mi-volumen:/datos ubuntu docker run -it -v C:\ruta\local:/ruta/en/contenedor ubuntu  Crear Volumenes docker volume create mi-volumen  Consultar informacion docker ps docker ps -a docker images  Imagen personalizadas docker commit mi-ubuntu mi-imagen-personalizada 

Operaciones con contendores docker start mi-ubuntu docker exec -it mi-ubuntu bash docker start -ai mi-ubuntu docker rm mi-ubuntu docker container prune 

En este ejercicio vamos a crear un documento donde documetaremos todos los pasos para la instalación de Docker en Windows y el uso de los contenedores.  

El profesor ira realizando las diferentes tareas y tu debera replicarlos y documentarlos en el documento. 

Tareas a documentar (Puedes combinar el uso de la interfaz y la linea de comandos) 

- Instalacion de docker en windows 

-Durante la instalación, Docker puede pedirte **habilitar WSL 2** (Windows Subsystem for Linux).  
Es **imprescindible** para que Docker funcione correctamente, ya que los contenedores utilizan un entorno Linux para ejecutarse.

-`C:\Users\pablo>docker --version`
`Docker version 28.5.1, build e180ab8`

    
- Que es el WSL y porque lo necesita docker en windows
-**WSL (Windows Subsystem for Linux)** es una capa que permite ejecutar un entorno Linux directamente en Windows sin necesidad de una máquina virtual completa.

Docker en Windows **usa WSL 2** para ejecutar sus contenedores, ya que Docker se basa en características del **kernel de Linux** (como cgroups, namespaces, etc.) que no existen de forma nativa en Windows.

-`C:\Users\pablo>wsl --status`
`Distribución predeterminada: Ubuntu`
`Versión predeterminada: 2`
    

- Examinar el Catalogo de imagenes en docker 
-Docker tiene un repositorio central llamado **Docker Hub**, disponible en:  https://hub.docker.com  

Aquí puedes buscar imágenes oficiales, por ejemplo:

- `ubuntu`
    
- `httpd` (servidor Apache)
    
- `mysql`
    
- `nginx`
    
- `python`

-También puedes buscar imágenes desde la terminal:

  `docker search ubuntu`

- Crear un contenedor Ubuntu 
-Primero, descargamos la imagen oficial:

  `docker pull ubuntu`

-Esto descarga la última versión de Ubuntu desde Docker Hub.

-Después, ejecutamos un contenedor interactivo:

`docker run -it ubuntu`

🔹 `-it` = modo interactivo + terminal.  
🔹 Esto abre una consola dentro del contenedor.  
🔹 Desde ahí puedes usar comandos Linux (`ls`, `cd`, `apt update`, etc.).

Para salir del contenedor:

`exit`

- Crear un contenedor Ubuntu poniéndole un nombre 
especifico

-`docker run -it --name mi-ubuntu ubuntu`
🔹 Crea y ejecuta un contenedor basado en la imagen `ubuntu`, asignándole el nombre **mi-ubuntu**.  
🔹 Así es más fácil identificarlo en los comandos posteriores.

Puedes comprobarlo con:

`docker ps -a`

- Intalar la extension Portainer 
    
-Buscamos Portainer en el docker desktop, en el apartado extensiones y lo instalamos

- Crear un volumen y asignárselo a un contenedor ubuntu para que sea persistente. 

-Un **volumen** es como una **carpeta persistente** que vive **fuera del contenedor**, pero que el contenedor puede **usar como si estuviera dentro**.

-Crear el volumen:

`docker volume create mi-volumen`

> 🔹 Crea un volumen persistente llamado **mi-volumen**, que guardará los datos aunque se borre el contenedor.

### Ejecutar contenedor usando ese volumen:

`docker run -it --name ubuntu-volumen -v mi-volumen:/datos ubuntu`

Creamos un contenedor que le da el nombre :
`ubuntu-volumen`

> 🔹 Monta el volumen **mi-volumen** en la ruta `/datos` dentro del contenedor.  
> 
> 🔹 Todo lo que guardes en `/datos` persistirá.
> 
> - Dentro del contenedor, todo lo que guardes en `/datos` se almacenará en ese volumen.
    
-Si borras el contenedor y creas otro que monte el mismo volumen, los datos seguirán ahí.

Verifica los volúmenes creados:

`docker volume ls`


- Crear un contenedor htpd en el puerto 8080 

-`docker run -d --name mi-web -p 8080:80 httpd`
    

- Instalar nano en el contenedor 
-Para acceder al contenedor:
`docker exec -it mi-web bash`

-`apt update && apt upgrade -y`
-`apt install nano -y`

    

- Cambiar el index del contenedor web creado 
    `cd /usr/local/apache2/htdocs`
    `nano index.html`


Indica los comandos o el modo de realizar las siguientes acciones desde la terminal y pon un ejemplo de uso y describe lo que hace:
