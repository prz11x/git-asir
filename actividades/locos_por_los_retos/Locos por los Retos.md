### Paso 1 de 80

EMPEZAREMOS TRABAJANDO EN NUESTRA MAQUINA VIRTUAL Kali (o Ubuntu)  
Primero vamos a Crear un entorno de red simple con servidores web para la busqueda de vulnerabilidades y monitorización.

**Crea un directorio para tu proyecto**: Ejemplo mkdir red1 

mkdir red1
cd red1


-Dentro crea las carpetas httpd y firewall

mkdir httpd firewall

-Dentro de la carpeta httpd crea un archivo **`Dockerfile-httpd`**


y inserta el siguiente código:

`# Dockerfile para httpd FROM httpd:latest COPY ./public-html/ /usr/local/apache2/htdocs/`

**NOTAS;**  
La instrucción `COPY ./public-html/ /usr/local/apache2/htdocs/` en el Dockerfile realiza lo siguiente:

- **`COPY`**: Es una instrucción de Dockerfile que copia archivos o directorios desde el sistema de archivos del host al sistema de archivos del contenedor.
- **`./public-html/`**: Especifica el directorio en tu máquina local que contiene los archivos que deseas copiar. En este caso, se asume que tienes un directorio llamado `public-html` en el mismo nivel que tu Dockerfile.
- **`/usr/local/apache2/htdocs/`**: Especifica el destino dentro del contenedor donde se copiarán los archivos. Este es el directorio predeterminado de documentos de Apache (`httpd`), donde se almacenan los archivos que se servirán como contenido web.

Crea otro  `Dockerfile-firewall en la carpeta del firewall y pon:`

```
# Dockerfile para el firewall
FROM ubuntu:latest
RUN apt-get update && apt-get install -y iptables
COPY ./firewall-rules.sh /usr/local/bin/firewall-rules.sh
RUN chmod +x /usr/local/bin/firewall-rules.sh
ENTRYPOINT ["/usr/local/bin/firewall-rules.sh"]
```

NOTAS;  
`RUN` en un Dockerfile se utiliza para ejecutar comandos dentro de la imagen durante el proceso de construcción. Es una de las instrucciones más comunes y permite instalar paquetes, configurar el sistema, y realizar otras tareas necesarias para preparar la imagen.

**La carpeta public-html debes crearela en la de httpd**
```
Crea un archivo llamado firewall-rules.sh dentro del subdirectorio firewall:
```

````

#!/bin/bash 
# Limpiar reglas existentes 
iptables -F  

# Permitir tráfico desde el host 
iptables -A INPUT -i eth0 -s 192.168.1.0/24 -j ACCEPT 

# Bloquear todo el tráfico restante iptables -A INPUT -j DROP       

Ejemplos y manual de iptables: [https://binariocero.com/linux/guia-de-uso-de-iptables-opciones-y-ejemplos Links to an external site.](https://binariocero.com/linux/guia-de-uso-de-iptables-opciones-y-ejemplos)   **OJO, ajusta las direcciones ip (la del host) a tu red actual**

````

Ahora vamos hacer el compose para el arranque de los contenedores.

Crea un archivo llamado `docker-compose.yml` en el directorio principal del proyecto

```
version: '3.8'

services:
  web1:
    build:
      context: ./httpd
      dockerfile: Dockerfile-httpd
    networks:
      mynetwork:
        ipv4_address: 172.20.0.2

  web2:
    build:
      context: ./httpd
      dockerfile: Dockerfile-httpd
    networks:
      mynetwork:
        ipv4_address: 172.20.0.3

  firewall:
    build:
      context: ./firewall
      dockerfile: Dockerfile-firewall
    cap_add:
      - NET_ADMIN
    networks:
      mynetwork:
        ipv4_address: 172.20.0.4

networks:
  mynetwork:
    driver: bridge
    ipam:
      config:
        - subnet: 172.20.0.0/16
```

  
**`docker-compose up --build`**
  
* Realiza pruebas de conexion entre el host y los contenedores.  
* En cada servidor web modifica el index.html para que ponga server1, server2 o lo que quieras.


Posibles problemas  
Para ver las redes: **sudo docker network ls**  
Si necesitas borrar la red: **sudo docker network  rm  nombreRed**  
Si teneis problemas de permisos usar siempre **sudo**Puede ser que tengas que cambiar el archivo de info del repositorio de parrot  
**/etc/containers/registries.conf  
**

[registries.search]

registries = ['docker.io','quay.io']
### Paso 2 de 80

Configurar un entorno de laboratorio con Parrot 

Crea una nueva carpeta para parrot y crea el dockerfile

#### Dockerfile para Parrot Security OS

```
# Usar la imagen oficial de Parrot Security OS
FROM parrotsec/security:latest

# Actualizar y instalar herramientas
RUN apt-get update && apt-get install -y \
    metasploit-framework \
    nmap \
    burpsuite \
    wireshark \
    john \
    hydra \
```

   `git \     vim \     wget &&` 

```
 # Establecer el punto de entrada CMD ["/bin/bash"]
```

Además de las herramientas mencionadas anteriormente, aquí tienes algunas adicionales que podrías considerar:

1. **Aircrack-ng**: Suite de herramientas para evaluar la seguridad de redes Wi-Fi.
2. **SQLmap**: Herramienta para detectar y explotar vulnerabilidades de inyección SQL.
3. **Nikto**: Escáner de servidores web para detectar vulnerabilidades.
4. **Gobuster**: Herramienta para la enumeración de directorios y archivos en servidores web.

####   
Ejecución del Contenedor

Para construir y ejecutar el contenedor:

```
# Construir la imagen
docker build -t parrot-pentest .

# Ejecutar el contenedor
docker run -it parrot-pentest
```
### Paso 3 de 80

EJERCICIO  
En los pasos anteriores has creado un docker-composer para montar 3 contenedores (dos de web y el firewall). Luego has creado un contenedor parrot con herramientas, pero si te fijas, este esta en una red diferente.  
  
Ahora debes modificar el YML del paso 1 para que cree todo el proceso del paso 1 y 2. Es decir los dos de web, el firewall y el parrot con los dokerfiles ya creados.  
  

|   |
|---|
|Ayudas<br><br>Si necesitas borrar la red: **sudo docker network  rm  nombreRed  <br>**Para eliminar un contenedor **sudo docker rm nombre o id del contenedor  <br>**Para parar un contenderor: **sudo docker stop nombre o id del contenedor  <br>**<br><br>Si quieres pararlos todos: **sudo docker stop $(docker ps -q)  <br>**Para borrar todos los de una red en concreto: **sudo docker rm -f nombreDeLaRed  <br>**|

Como vamos a usar parrot para administrar la red, debemos indicarle tanot que sea interactivo como que es una maquina administradora.

Ejemplo:

|   |
|---|
|version: '3.8'<br><br>services:  <br>  # Servicio web1  <br>  web1:  <br>    build:  <br>      context: ./httpd  <br>      dockerfile: Dockerfile-httpd  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.2<br><br>  # Servicio web2  <br>  web2:  <br>    build:  <br>      context: ./httpd  <br>      dockerfile: Dockerfile-httpd  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.3<br><br>  # Servicio de firewall  <br>  firewall:  <br>    build:  <br>      context: ./firewall  <br>      dockerfile: Dockerfile-firewall  <br>    cap_add:  <br>      - NET_ADMIN  # Esto otorga privilegios para manipular la red  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.4<br><br>  # Nuevo contenedor Parrot Security OS  <br>  parrot:  <br>    build:  <br>      context: ./parrotsec  # Ubicación del Dockerfile para Parrot Security OS  <br>      dockerfile: Dockerfile-parrot  # Nombre del Dockerfile que contiene las instrucciones  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.5  <br>    cap_add:  <br>      - SYS_ADMIN  # Permite algunos privilegios elevados si es necesario para Parrot OS  <br>    stdin_open: true  # Mantiene la terminal abierta para interacciones  <br>    tty: true  # Habilita un terminal interactivo<br><br># Definición de la red "mynetwork"  <br>networks:  <br>  mynetwork:  <br>    driver: bridge  <br>    ipam:  <br>      config:  <br>        - subnet: 172.20.0.0/16  # Subred definida para la red interna|

El dockerfile, tambien tenemos que prepararlo para el yml, cambiando o poniendo el nombre del archivo. Crea una carpeta llamada `parrotsec` en tu proyecto y dentro de ella coloca el `Dockerfile-parrot`

|   |
|---|
|# Usar la imagen oficial de Parrot Security OS  <br>FROM parrotsec/security:latest<br><br># Actualizar y instalar herramientas  <br>RUN apt-get update && apt-get install -y \  <br>    metasploit-framework \  <br>    nmap \  <br>    burpsuite \  <br>    wireshark \  <br>    john \  <br>    hydra \  <br>    git \  <br>    vim \  <br>    wget \  <br>    && apt-get clean<br><br># Establecer el directorio de trabajo  <br>WORKDIR /root<br><br># Ejecutar un comando para mantener el contenedor activo  <br>CMD [ "bash" ]|

`docker-compose up` en el directorio de tu proyecto. Docker Compose construirá y levantará todos los contenedores

Dentro del conetenedor de parrot ejecuta un nmap para rastrear todas las ips de la red en la que se encuentra.

**nmap -sn direccionRed/mascara**  
Ejemplo: nmap -sn 172.10.0.0/24

Ayudas

Si necesitas borrar la red: **sudo docker network  rm  nombreRed  
**Para eliminar un contenedor **sudo docker rm nombre o id del contened**

### Paso 4 de 80

Ahora vamos a realizar  diferentes rastreos con nmap en la subred.  
 

#### 1. **Escaneo básico de una dirección IP en la red**:

Supongamos que quieres escanear la dirección IP `172.20.0.2` (que es la dirección IP de tu contenedor `web1`). Puedes ejecutar el siguiente comando en el contenedor de Parrot:

`nmap 172.20.0.2`

Este comando realiza un escaneo básico de los puertos más comunes del contenedor `web1`.

#### 2. **Escaneo de todos los puertos**:

Si quieres hacer un escaneo de todos los puertos de una máquina en la red, puedes usar la opción `-p-` para escanear todos los puertos (del 1 al 65535):

`nmap -p- 172.20.0.2`

Este comando escaneará todos los puertos de la IP `172.20.0.2` (el contenedor `web1`) para verificar cuáles están abiertos.

#### 3. **Escaneo detallado con información de servicios**:

Si quieres obtener más información acerca de los servicios que están corriendo en un contenedor (por ejemplo, versión del servicio), puedes usar la opción `-sV` que realiza un escaneo de versión de servicios:

`nmap -sV 172.20.0.2`

Esto no solo escaneará los puertos, sino que también intentará detectar la versión de los servicios que están corriendo en esos puertos.

#### 4. **Escaneo con detección de sistema operativo**:

Puedes agregar la opción `-O` para intentar detectar el sistema operativo que está ejecutando el contenedor o la máquina objetivo:

`nmap -O 172.20.0.2`

Este comando intentará identificar el sistema operativo basado en el comportamiento de los puertos y las respuestas de red.

#### 5. **Escaneo en una subred completa**:

Si deseas escanear toda la red para encontrar qué dispositivos están activos y qué puertos están abiertos, puedes especificar una subred completa. Por ejemplo, si quieres escanear la subred `172.20.0.0/24`, puedes usar el siguiente comando:

`nmap 172.20.0.0/24`

Este comando escaneará todas las direcciones IP de `172.20.0.1` a `172.20.0.254` dentro de la subred `172.20.0.0/24` para ver qué máquinas están activas y qué puertos tienen abiertos.

#### 6. **Escaneo de puertos específicos**:

Si solo te interesa escanear puertos específicos, puedes usar la opción `-p` para definir los puertos que quieres escanear. Por ejemplo, si solo te interesa saber si el puerto 80 (HTTP) y 443 (HTTPS) están abiertos en `web1`, puedes hacer lo siguiente:

`nmap -p 80,443 172.20.0.2`

Este comando solo escaneará los puertos 80 y 443 en la IP `172.20.0.2`.

#### 7. **Escaneo silencioso (sin descubrimiento de hosts)**:

Si quieres escanear sin enviar demasiada información a la red (es decir, hacer un escaneo "silencioso"), puedes usar la opción `-Pn` que desactiva el descubrimiento de hosts (es decir, no verificará si la máquina está en línea antes de hacer el escaneo):

`nmap -Pn 172.20.0.2`

Esto puede ser útil si deseas realizar un escaneo sin alertar a sistemas de monitoreo de red, ya que `nmap` no enviará un paquete de "descubrimiento" al objetivo.

#### 8. **Escaneo de una máquina con múltiples servicios (ejemplo de escaneo en Parrot)**:

Si quisieras escanear tu red en busca de múltiples servicios o máquinas y visualizar la topología de red de manera más detallada, puedes usar el comando siguiente para hacer un escaneo más avanzado:

`nmap -sS -sV -O -T4 172.20.0.0/24`

Este comando hace lo siguiente:

- `-sS`: Escaneo de tipo "SYN" (más sigiloso).
- `-sV`: Detección de versiones de servicios.
- `-O`: Detección del sistema operativo.
- `-T4`: Ajusta la velocidad del escaneo para hacerlo más rápido.

Escaneará toda la subred `172.20.0.0/24` buscando máquinas activas y proporcionando información detallada sobre los servicios y sistemas operativos detectados.

#### 9. **Escaneo de vulnerabilidades con scripts Nmap (NSE)**:

Nmap incluye una gran cantidad de scripts de detección de vulnerabilidades que puedes ejecutar con el parámetro `--script`. Por ejemplo, para detectar posibles vulnerabilidades relacionadas con SMB, puedes ejecutar el siguiente comando:

`nmap --script smb-vuln* 172.20.0.2`

Esto ejecutará todos los scripts de vulnerabilidades SMB que están disponibles en Nmap y tratará de detectar cualquier posible problema en el puerto SMB (445).

#### Cómo ejecutar los comandos en tu contenedor Parrot:

1. **Accede al contenedor Parrot** desde tu máquina host con el siguiente comando:
    
    `docker exec -it <nombre_o_id_del_contenedor_parrot> bash`
    
    El nombre del contenedor lo puedes obtener con `docker ps`.
    
2. Una vez dentro del contenedor, simplemente ejecuta los comandos de `nmap` como si estuvieras en cualquier otra máquina Linux.
    

- **Red interna de Docker**: Todos estos escaneos solo serán visibles dentro de la red interna de Docker. Si estás ejecutando estos escaneos desde el contenedor Parrot, solo podrás escanear los contenedores que estén en la misma red (en este caso, `mynetwork`).


### Paso 5 de 80

Vamos a ver ahora el trafico que se produce dentro de la red, para ello usremos wiresark desde la terminal del contenedor de parrot.

Primero verifica las interfaces de red dentro del contenedor:

`ip a`

Normalmente, la interfaz  será `eth0`. Puedes verificar el tráfico de la subred capturando paquetes en esta interfaz.

---

#### 3️⃣ **Capturar tráfico con Wireshark**

Dentro del contenedor Parrot, puedes iniciar **Wireshark en modo terminal** con `tshark`:

`tshark -i eth0`

---

#### 4️⃣ **Generar tráfico entre los contenedores**

Desde otro terminal, puedes generar tráfico entre los servicios. Por ejemplo, desde `web1` puedes hacer peticiones a `web2`:

`docker exec -it web1 curl http://172.20.0.3`

O puedes hacer un `ping` desde Parrot a `web2`:

`ping -c 4 172.20.0.3`

Si quieres capturar únicamente tráfico HTTP (puerto 80), puedes ejecutar:

`tshark -i eth0 port 80`

Para tráfico ICMP (pings):

`tshark -i eth0 icmp`

---

#### 5️⃣ **Filtrar tráfico en Wireshark**

En la interfaz gráfica de Wireshark, puedes filtrar paquetes con:

- `ip.addr == 172.20.0.2` → Para ver solo tráfico de `web1`
- `ip.addr == 172.20.0.3` → Para ver tráfico de `web2`
- `http` → Para capturar solo tráfico HTTP
- `icmp` → Para capturar solo tráfico de ping

### Paso 6 de 80

Ahora vamos a hacerlo, pero fuera de la subred

#### **Identificar la interfaz de Docker en el host**

Docker usa una interfaz virtual llamada **bridge** para manejar redes internas. Para encontrar la interfaz de la red `mynetwork` (172.20.0.0/16), ejecuta:

`ip a | grep docker`

Verás algo como esto:

`3: docker0: <BROADCAST,MULTICAST,UP,LOWER_UP> mtu 1500 qdisc noqueue state UP inet 172.17.0.1/16 brd 172.17.255.255 scope global docker0`

- Si la red `mynetwork` no usa la interfaz `docker0`, identifica su nombre con:

`docker network inspect mynetwork | grep Gateway`

El resultado te dará el **Gateway** y te ayudará a identificar la interfaz.

---

####  **Capturar tráfico con Wireshark en el host**

Abre Wireshark en tu sistema Parrot y selecciona la interfaz de red de Docker, por ejemplo:

- `docker0`
- `br-xxxxxxxxxxxx` (si Docker creó una interfaz bridge específica)

Puedes verificar qué interfaces están activas con:

`ip link show`

---

#### **Filtrar tráfico de la subred Docker**

Usa estos filtros en Wireshark para ver solo el tráfico de la subred `172.20.0.0/16`:

- Capturar **todo el tráfico de Docker**:
    
    `ip.addr == 172.20.0.0/16`
    
- Capturar **tráfico HTTP entre los contenedores**:
    
    `tcp.port == 80`
    
- Capturar **pings (ICMP) entre contenedores**:
    
    `icmp`
    

No olvides generar trafico entre los contenedores mientras haces el rastreo.


### Paso 7 de 80

En el contenedor Web 1 vamos a instalar PHP para Apache, y vamos a poner una pagina que identifique la ubicación del usuario que hace la petición.

Aquí tienes un ejemplo de como hacerlo.

|   |
|---|
|<?php  <br>function getUserLocation($ip) {  <br>    $url = "http://ip-api.com/json/{$ip}?fields=status,country,regionName,city,query";  <br>    $response = file_get_contents($url);  <br>    $data = json_decode($response, true);<br><br>    if ($data['status'] == 'success') {  <br>        return [  <br>            'ip' => $data['query'],  <br>            'country' => $data['country'],  <br>            'region' => $data['regionName'],  <br>            'city' => $data['city']  <br>        ];  <br>    } else {  <br>        return null;  <br>    }  <br>}<br><br>// Obtener la IP del usuario  <br>$ip = $_SERVER['REMOTE_ADDR'];  <br>$location = getUserLocation($ip);<br><br>if ($location) {  <br>    echo "IP: " . $location['ip'] . "<br>";  <br>    echo "País: " . $location['country'] . "<br>";  <br>    echo "Región: " . $location['region'] . "<br>";  <br>    echo "Ciudad: " . $location['city'] . "<br>";  <br>} else {  <br>    echo "No se pudo determinar la ubicación.";  <br>}  <br>?>|

Para acceder desde el **navegador del host** a la página PHP alojada en uno de tus contenedores `web1` o `web2`, sigue estos pasos:

#### **Exponer el puerto del servidor web en Docker Compose**

Actualmente, en tu `docker-compose.yml`, los servicios `web1` y `web2` **no tienen puertos expuestos al host**. Para hacerlo, edita el archivo y agrega la sección `ports`:  
  

|   |
|---|
|services:  <br>  web1:  <br>    build:  <br>      context: ./httpd  <br>      dockerfile: Dockerfile-httpd  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.2  <br>    ports:  <br>      - "8080:80"  # Expone el puerto 80 del contenedor como 8080 en el host<br><br>  web2:  <br>    build:  <br>      context: ./httpd  <br>      dockerfile: Dockerfile-httpd  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.3  <br>    ports:  <br>      - "8081:80"  # Expone el puerto 80 del contenedor como 8081 en el host|

Con esta configuración:

- `web1` será accesible desde el host en `http://localhost:8080`
- `web2` será accesible desde el host en `http://localhost:8081`

### **Aplicar los cambios y reiniciar los contenedores**

Después de editar `docker-compose.yml`, aplica los cambios reiniciando los contenedores:

`docker-compose down`

`docker-compose up -d`


### Paso 8 de 80

Ahora vamos a subir un conjunto de contenedores a **Docker Hub** junto con su `docker-compose.yml`

Si no tienes una cuenta en **Docker Hub**, regístrate en https://hub.docker.com/.

Luego, inicia sesión en Docker desde la terminal: 

`docker login`

Docker Compose usa imágenes personalizadas (`build`), así que primero debes **crear y etiquetar** las imágenes correctamente con tu usuario de Docker Hub.

Edita el `docker-compose.yml` para usar imágenes con nombres de Docker Hub:

|   |
|---|
|services:  <br>  web1:  <br>    build:  <br>      context: ./httpd  <br>      dockerfile: Dockerfile-httpd  <br>    image: tu_usuario_docker/web1:latest  # Define la imagen con tu usuario  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.2  <br>    ports:  <br>      - "8080:80"<br><br>  web2:  <br>    build:  <br>      context: ./httpd  <br>      dockerfile: Dockerfile-httpd  <br>    image: tu_usuario_docker/web2:latest  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.3  <br>    ports:  <br>      - "8081:80"<br><br>  firewall:  <br>    build:  <br>      context: ./firewall  <br>      dockerfile: Dockerfile-firewall  <br>    image: tu_usuario_docker/firewall:latest  <br>    cap_add:  <br>      - NET_ADMIN  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.4<br><br>  parrot:  <br>    build:  <br>      context: ./parrotsec  <br>      dockerfile: Dockerfile-parrot  <br>    image: tu_usuario_docker/parrotsec:latest  <br>    networks:  <br>      mynetwork:  <br>        ipv4_address: 172.20.0.5  <br>    cap_add:  <br>      - SYS_ADMIN  <br>    stdin_open: true  <br>    tty: true<br><br>networks:  <br>  mynetwork:  <br>    driver: bridge  <br>    ipam:  <br>      config:  <br>        - subnet: 172.20.0.0/16|

Con esta configuración, cada servicio tendrá una imagen específica en **Docker Hub**.

Ejecuta los siguientes comandos para construir y etiquetar cada imagen:

docker build -t tu_usuario_docker/web1:latest ./httpd -f Dockerfile-httpd  
docker build -t tu_usuario_docker/web2:latest ./httpd -f Dockerfile-httpd  
docker build -t tu_usuario_docker/firewall:latest ./firewall -f Dockerfile-firewall  
docker build -t tu_usuario_docker/parrotsec:latest ./parrotsec -f Dockerfile-parrot

Una vez construidas las imágenes, súbelas a **Docker Hub** con:

docker push tu_usuario_docker/web1:latest  
docker push tu_usuario_docker/web2:latest  
docker push tu_usuario_docker/firewall:latest  
docker push tu_usuario_docker/parrotsec:latest

#### Subir el `docker-compose.yml` a un repositorio

Docker Hub no almacena archivos de configuración, por lo que es recomendable subir el `docker-compose.yml` a **GitHub**

Debes crear un repositorio Git o trasladar el archivo yml a un repositorio que tengas.

Luego lo añades y haces el push como siempre

### Paso 9 de 80

 Pidele la url del repositorio donde tenga el compose a un compañero.

Crea una nueva carpeta y ejecuta el compose  
  
git clone https://github.com/tu_usuario/docker-compose-repo.git  
cd docker-compose-repo  
docker-compose up -d

_Nota: como lo que estas descargando es el yml, puedes modificarlo a tu gusto antes de hacer el up._