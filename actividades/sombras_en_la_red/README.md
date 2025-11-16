1.Preparar la VM / entorno (instalar Docker).

2.Crear la estructura de carpetas del proyecto.

3.Escribir los Dockerfile y ficheros mínimos (index.php, init.sql, vsftpd.conf, etc.).

4.Crear red y volúmenes Docker.

5.Construir las imágenes (docker build).

6.Arrancar los contenedores (docker run) en el orden recomendado.

7.Probar conectividad básica (navegar a web, acceder a MySQL, FTP, Maildev).

8.Ejecutar las pruebas/reto (recon, FTP, SQLi desde Kali).

9.Recolectar logs / evidencias.

10.Hardening / mitigaciones y documentación / entrega.


Detalle con comandos y por qué (paso a paso)

1) Preparación en la VM (comandos iniciales)
# Actualizar y asegurarnos de tener Docker
sudo apt update && sudo apt install -y docker.io
# Iniciar y activar Docker
sudo systemctl enable --now docker
# Añadir tu usuario al grupo docker (opcional, cerrar sesión/reiniciar)
sudo usermod -aG docker $USER
# Crear red y volumenes
docker network create cybernet
docker volume create mysql_data
docker volume create web_html
2) Estructura de carpetas (ejemplo)/home/usuario/hack-containers/
├─ web/
│  ├─ Dockerfile
│  └─ html/
│     ├─ index.php
│     └─ secret/flag.txt
├─ mysql/
│  ├─ Dockerfile
│  └─ init-db/
│     └─ init.sql
├─ ftp/
│  └─ Dockerfile
├─ mail/
│  └─ Dockerfile
├─ attacker/
│  └─ Dockerfile
└─ logger/
   └─ Dockerfile

3) Dockerfile + archivos — ejemplos
A) MySQL (mysql/Dockerfile)
Usamos la imagen oficial de MySQL como base, y añadimos un init SQL.

FROM mysql:8.0
# Copiamos scripts de inicialización (se ejecutan al primer arranque)
COPY init-db/ /docker-entrypoint-initdb.d/ VOLUME ["/var/lib/mysql"]
EXPOSE 3306
mysql/init-db/init.sql (ejemplo con credenciales débiles y tabla)

CREATE DATABASE empleados;
USE empleados;
CREATE TABLE usuarios ( id INT AUTO_INCREMENT PRIMARY KEY, usuario VARCHAR(50), password VARCHAR(50) );
INSERT INTO usuarios (usuario, password) VALUES ('admin','admin123');
Construir y ejecutar:

cd mysql
docker build -t ctf-mysql:1.0 . docker run -d --name mysql --network cybernet \
-e MYSQL_ROOT_PASSWORD=rootroot \
-v mysql_data:/var/lib/mysql \
ctf-mysql:1.0
B) Web vulnerable (PHP + Apache) (web/Dockerfile)
FROM php:8.2-apache
# Copiamos el código vulnerable
COPY html/ /var/www/html/
# Habilitamos mod_rewrite por si hace falta
RUN a2enmod rewrite
EXPOSE 80
web/html/index.php (vulnerable ejemplo: login sin sanitizar — para SQLi)

<?php
$conn = new mysqli('mysql','root','rootroot','empleados');
if(isset($_GET['user'])){
    $user = $_GET['user'];
    $res = $conn->query("SELECT * FROM usuarios WHERE usuario = '$user'");
    if($res && $res->num_rows){
        echo "Usuario encontrado<br>";
    } else {
        echo "No encontrado";
    }
} else {
    echo '<a href="?user=admin">Comprobar usuario admin</a>';
}
?>
web/html/secret/flag.txt

FLAG{has_encontrado_la_bandera_web}
Construir y ejecutar:

cd web
docker build -t ctf-web:1.0 .
docker run -d --name web --network cybernet \
  -p 8080:80 \
  -v web_html:/var/www/html \
  ctf-web:1.0
# Copiar el html local al volumen (una sola vez si quieres)
docker cp html/. web:/var/www/html/
Nota: en index.php se conecta a host mysql (nombre del contenedor) — así los containers se resuelven por nombre dentro de la red cybernet.
C) FTP (ftp/Dockerfile) — ejemplo simple con vsftpd
FROM alpine:3.18
RUN apk add --no-cache vsftpd openrc
COPY ftp-config/vsftpd.conf /etc/vsftpd/vsftpd.conf
# Creamos usuario demo
RUN adduser -D -h /home/usuario ftpuser && \
    echo "ftpuser:ftp123" | chpasswd && \
    mkdir -p /home/usuario/ftp_upload && chown ftpuser:ftpuser /home/usuario/ftp_upload
EXPOSE 21 20
CMD ["sh", "-c", "vsftpd /etc/vsftpd/vsftpd.conf"]
 

 

ftp-config/vsftpd.conf puede ser muy simple para prácticas. (Puedes proveerlo a los alumnos o generar uno básico.)

Construir y ejecutar:

cd ftp
docker build -t ctf-ftp:1.0 .
docker run -d --name ftp --network cybernet -p 2121:21 ctf-ftp:1.0
(Nota: FTP activo/pasivo considera puertos extra; para la práctica usarlo localmente y documentar.)

D) Maildev (mail server de pruebas) — Dockerfile mínimo
FROM maildev/maildev:latest EXPOSE 1080 25
Construir y ejecutar:

cd mail
docker build -t ctf-mail:1.0 .
docker run -d --name mail --network cybernet -p 1080:1080 ctf-mail:1.0
Maildev ofrece UI web en :1080 para ver emails enviados por la aplicación vulnerable.

E) Logger / ELK ligero (logger/Dockerfile) — ejemplo usando busybox+fluentd sería largo.
Puedes usar Fluentd o simplemente montar un contenedor que recoja docker logs desde fuera. Para simplicidad, propondré un contenedor fluentd base:

FROM fluent/fluentd:v1.14-1
EXPOSE 24224
Construir y ejecutar:

cd logger
docker build -t ctf-logger:1.0 .
docker run -d --name logger --network cybernet -p 24224:24224 ctf-logger:1.0
F) Contenedor atacante (Kali) (attacker/Dockerfile)
Instalamos unas herramientas útiles (nmap, nikto, sqlmap, hydra).

FROM kalilinux/kali-rolling
RUN apt update && apt install -y nmap nikto sqlmap hydra netcat-openbsd curl && apt clean
WORKDIR /root
CMD ["/bin/bash"]
Construir y ejecutar (modo interactivo):

cd attacker
docker build -t ctf-kali:1.0 .
docker run -it --name kali --network cybernet ctf-kali:1.0
Dentro del contenedor atacas la red cybernet (ej. nmap -sV -p- web).

4) Notas sobre volúmenes y persistencia
Mysql usa -v mysql_data:/var/lib/mysql para persistencia.

Web usamos -v web_html:/var/www/html para poder editar fuera y reflejar cambios.

Para copiar archivos iniciales al volumen, puedes docker cp como mostré.

5) RECUERDA
# Ver containers
docker ps -a

# Logs de un contenedor
docker logs web

# Entrar a un contenedor
docker exec -it mysql bash
docker exec -it kali bash

# Consultar red
docker network inspect cybernet

# Inspeccionar variables de entorno de un contenedor
docker inspect -f '{{range .Config.Env}}{{println .}}{{end}}' mysql
