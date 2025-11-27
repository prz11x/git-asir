
# Índice

- [[#$.Introducción]]
- [[#1. Conexión SSH]]
- [[#2. Instalación Apache2]]
- [[#3. Instalación de MySQL-Server]]
  - [[#3.1 Conexión remota a MySQL]]
  - [[#3.2 Creación de usuario para MySQL]]
  - [[#3.3 Conexión desde el cliente con Workbench]]
- [[#4. Instalación de servicio FTP]]
  - [[#4.1 Acceder vía FTP desde el cliente]]
  - [[#4.2 Acceso a la carpeta WEB de Apache2]]
- [[#5. Instalación del Intérprete PHP]]
- [[#6. Acceso por SSH desde un IDE - VSCode]]


# $.Introducción

En esta documentación vamos a explicar el proceso y comandos básicos para instalar los servicios en un Ubuntu Server.  

Para ello partiremos de un Ubuntu server al cual accederemos vía SSH.  

Implantaremos varios servicios y tecnologías, tanto en el server como en el host para realizar nuestras pruebas y ver que todo funcione correctamente.

Aquí un breve resumen de ellos: 

SERVER 

Primero sudo apt update y sudo apt upgrade.

-SSH  
-Apache2  
-Mysql  
-FTP  
-PHP

HOST  

-CMD o Putty para conexión SSH  
-Workbench  
-VSCODE via SSH  
-Filezilla cliente

En la primera fase, prepararemos el sistema para acceso remoto a los servicios con seguridad  
baja. En la segunda fase daremos seguridad al servidor.  

[⬆️ Volver al índice](#índice)

# **1. Conexión SSH**  

En el servidor linux instalaremos el servicio ssh con :  

  `sudo apt install ssh`  
  
![[Pasted image 20250923130529.png]]

Si estamos con ip dinamica, tenemos que ver la ip que se le asigne al servidor. Con comandos como:  
 
`ifconfig o ip a`

Si nos da error `ifconfig` es que no tenemos las net-tools instaladas. Las instalaremos con: 

`sudo apt install net-tools`

![[Pasted image 20250923130840.png]]

![[Pasted image 20250923130940.png]]

Y ahora si podemos ver la ip que nos dan:

![[Pasted image 20250923132029.png]]


Ahora ya tenemos instalado el servicio y hemos comprobado la ip del servidor.
Vamos a acceder desde terminal de windows o putty 

`c:>ssh usuario@ipdelservidor` 

![[Pasted image 20250923131838.png]]
  
Ponemos la contraseña y confirmamos con 'yes' que nos queremos conectar. De momento lo tenemos sin securizar.  


![[Pasted image 20250923132156.png]]

[⬆️ Volver al índice](#índice)
# 2. **Instalación APACHE2**  

Instalamos APACHE en el servidor con:


Esto permitirá que nuestro servidor pueda responder a peticiones por el puerto 80 desde un navegador cliente.  

![[Pasted image 20250923135250.png]]

Para ver si funciona correctamente, ponemos la ip del servidor en el explorador del host.  


![[Pasted image 20250923135432.png]]

Ahora si accedemos a la carpeta del servidor /var/www/html podemos crear archivos html para  
visualizar documentos web desde cualquier explorador.

![[Pasted image 20250923141212.png]]

![[Pasted image 20250923141306.png]]

![[Pasted image 20250923141402.png]]

Por ejemplo si modificamos el index.html podemos ver representados los cambios en nuestra web.

![[Pasted image 20250923141112.png]]


![[Pasted image 20250923141449.png]]

[⬆️ Volver al índice](#índice)

# **3. Instalación de Mysql-server**  

Instalamos mysql-server en el servidor con:
````
sudo apt install mysql-server
````

![[Pasted image 20250924114121.png]]

Una vez instalado ejecutamos el script:

````
sudo mysql_secure_installation
````

Este script nos realizara algunas preguntas de la configuración básica de mysql.

 1."Set root password? [Y/n]" - Esta opción te permite establecer una contraseña para el  
usuario root de MySQL/MariaDB. Se recomienda encarecidamente establecer una  
contraseña segura para proteger tu base de datos.

````
NOTA: ver el siguiente punto sobre politicas de contraseñas para  
entender mejor este apartado
````


2."Remove anonymous users? [Y/n]" - Esta opción elimina todas las cuentas de usuario  
anónimas, lo que significa que solo los usuarios con credenciales válidas podrán iniciar  
sesión en MySQL/MariaDB.  



3."Disallow root login remotely? [Y/n]" - Esta opción desactiva el inicio de sesión remoto del  
usuario root , lo que significa que solo se puede iniciar sesión como root desde el  
servidor local.  


4."Remove test database and access to it? [Y/n]" - Esta opción elimina la base de datos de  
prueba y cualquier cuenta de usuario relacionada con ella, lo que reduce la superficie de  
ataque potencial de la instalación de MariaDB.  



5."Reload privilege tables now? [Y/n]" - Esta opción recarga las tablas de privilegios de  
MySQL/MariaDBpara que los cambios que hayas realizado se hagan efectivos

![[Pasted image 20250924135856.png]]

![[Pasted image 20250924135927.png]]
![[Pasted image 20250924135952.png]]

Al ejecutar ````mysql_secure_installation````, se te presentarán estas opciones en el orden que se muestra arriba. Para seleccionar una opción, simplemente escribe Y o N y presiona Enter. Si  
no estás seguro de qué opción elegir, se recomienda seguir las opciones predeterminadas y  
seleccionar Y para cada una de las preguntas.  

En resumen, ````
mysql_secure_installation```` es un script útil que te ayuda a configurar una  
instalación de MySQL/MariaDBde manera segura y a proteger tu base de datos de posibles  
ataques.  

Una vez termine, reiniciamos el servicio.

`sudo systemctl restart mysql` 

Ahora ya podemos entrar en mysql desde el servidor.  
 
``sudo mysql -u root -p`` 

![[Pasted image 20250925091602.png]]

## **3.1 Conexión remota a mysql**  

Con la instalación por defecto podemos acceder a las bases de datos desde el localhost,  
incluso podríamos instalar en el mismo sistema algún gestor web como phpmyadmin. Pero es  
muy común permitir acceso maquinas cliente con programas como Workbench, DataGrip, etc...  
Pero primero tenemos que hacer algunas configuraciones.

``Archivo de configuración de mysql en Ubunut 24  ``
``**Para activar esto, abre tu archivo `mysqld.cnf`:**``

``sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf``

![[Pasted image 20250925094453.png]]

Allí debes ubicar la línea que empieza con la directiva bind-address .  
Por defecto, el valor asignado es 127.0.0.1 .  
asignamos el valor como * , :: , o 0.0.0.0

![[Pasted image 20250925095117.png]]

![[Pasted image 20250925095349.png]]

De este modo estamos permitiendo que cualquier dispositivo pueda conectarse desde  
cualquier IP.

Luego debemos crear un usuario para acceder a la base de datos diferente al ROOT. Pero al  
crear un usuario y asignarle una contraseña lo primero que tenemos que definir es la política  
de contraseñas de nuestro gestor que por defecto esta en MEDIUM.  
Políticas de contraseñas de MySQL a LOW = 0 / MEDIUM = 1 / STRONG = 2.

-Podemos cambiar la política de la contraseña con:

![[Pasted image 20250925092627.png]]


Para cerciorarnos de la que tenemos configurada actualmente podemos usar:

``SHOW VARIABLES LIKE 'validate_password%';``

![[Pasted image 20250925093215.png]]

Otra forma de cambiar la politica de contraseñas sería:

``mysql>SET GLOBAL validate_password.policy=0;  
``mysql>SET GLOBAL validate_password.policy=1;``  
``mysql>SET GLOBAL validate_password.policy=2;``

También podemos personalizar los requisitos de cada una, ejemplo:

``mysql>SET GLOBAL validate_password.length = 6;``
`
`mysql>SET GLOBAL validate_password.number_count = 0;`

Ahora que las reglas para una contraseña válida están claras, puedes crear un usuario con una  
contraseña válida


## **3.2 Creación de usuario para MySql**  

Para crear un usuario lo haremos en diferentes pasos.

``CREATE USER 'nombreusuario'@'ip_servidor_remoto' IDENTIFIED BY 'password'``

![[Pasted image 20250925100628.png]]

Vemos que las contraseñas deben cumplir con los requisitos en base al nivel que hemos elegido en mi caso MEDIUM. 

En mi caso para acordarme mejor de mi contraseña voy a modificar el parámetro de los caracteres especiales a 0 para que me permita mi contraseña en nivel MEDIUM sin necesitar caracteres especiales.

``set global validate_password.special_char_count = 0;``

y comprobamos que se han hecho los cambios.

``SHOW VARIABLES LIKE 'validate_password%';``

![[Pasted image 20250925100648.png]]

Al crear el usuario ponemos entre la @ primero el nombre y luego desde donde vamos a  
permitir que se conecte. si es desde localhost, una ip determinada o cualquier sitio.

````
-Ejemplos:

Por ejemplo aquí estamos creando un usuario con contraseña MEDIUM y que solo se pueda conectar desde localhost. 

``CREATE USER 'user'@'127.0.0.1' IDENTIFIED BY 'Estudiante123@'``
````


Crearemos un usuario con contraseña MEDIUM y que se pueda conectar desde cualquier host (%). 

``CREATE USER 'user'@'%' IDENTIFIED BY 'Abcd1234';``

![[Pasted image 20250925100730.png]]

El siguiente paso es definir los permisos de dicho usuario.
````
Estas son algunas opciones
`GRANT CREATE, ALTER, DROP, INSERT, UPDATE, DELETE, SELECT, REFERENCES, RELOAD on  
*.* TO 'user'@'%' WITH GRANT OPTION;`
````

Cambia el nombre de usuario y el dominio según sea tu caso.

 ``grant all privileges on *.* to 'user'@'%' with grant option;``

Finalmente ejecutamos:

``flush privilages;``

![[Pasted image 20250925102425.png]]

Reiniciamos para actualizar y que se hagan los cambios que hemos realizado:

``sudo systemctl restart mysql``

![[Pasted image 20250925111016.png]]

## **3.3 Conexión desde el cliente con Workbench**  

Ahora que ya tenemos configurado nuestro gestor de mysql y hemos puesto que se pueda  
acceder desde cualquier Ip, instalamos Workbench en nuestro host y nos conectamos

![[Pasted image 20250925103156.png]]

![[Pasted image 20250925112353.png]]

[⬆️ Volver al índice](#índice)
# **4. Instalación de servicio FTP** 

Para dar servido de transmisión de ficheros FTP a nuestro servidor, instalaremos VSFTPD

``sudo apt install vsftpd``

Lo siguiente que tenemos que hacer es modificar el archivo de configuración original. Dado  
que este archivo lo podremos necesitar después. Generemos una copia de nuestro archivo  
original.

``sudo cp /etc/vsftpd.conf /etc/vsftpd.conf_old``

![[Pasted image 20250925114455.png]]

![[Pasted image 20250925114536.png]]

Ahora editamos el archivo para

``sudo nano /etc/vsftpd.conf``
![[Pasted image 20250925115442.png]]

Copie la siguiente configuración en el archivo vsftpd.conf , guarda y cierra el archivo.

````
listen=NO  
listen_ipv6=YES
anonymous_enable=NO  
local_enable=YES  
write_enable=YES  
local_umask=022  
dirmessage_enable=YES  
use_localtime=YES  
xferlog_enable=YES  
connect_from_port_20=YES  
chroot_local_user=YES  
secure_chroot_dir=/var/run/vsftpd/empty  
pam_service_name=vsftpd  
rsa_cert_file=/etc/ssl/certs/ssl-cert-snakeoil.pem  
rsa_private_key_file=/etc/ssl/private/ssl-cert-snakeoil.key  
ssl_enable=NO  
pasv_enable=Yes  
pasv_min_port=10000  
pasv_max_port=10100  
allow_writeable_chroot=YES
````


![[Pasted image 20250925115522.png]]

Como hemos realizado una copia de seguridad podemos borrar su contenido, otro truco para  
muchos archivos de configuración en linux es comentar las lineas anteriores con #, o  
simplemente poner la nueva configuración al final del documento.  
*Ubuntu en algunas versiones, viene con un firewall llamado UFW. En este caso le debemos de  
decir que abra el puerto 20, 21 y los del 10000 al 10100 para su correcto funcionamiento:

``sudo ufw allow from any to any port 20,21,10000:10100 proto tcp``

![[Pasted image 20250925120532.png]]

Con el archivo de configuración grabado, solo resta reiniciar el servicio VSFTPD para que tome las nuevas reglas :

``sudo systemctl restart vsftpd``

![[Pasted image 20250925120612.png]]

## **4.1 Acceder vía FTP desde el cliente**  

Para transmitir archivos via FTP al servidor podemos usar cualquier programa de transmisión  
de archivos como Filezilla-client o winSCP.

![[Pasted image 20251001123101.png]]


![[Pasted image 20251001123408.png]]

![[Pasted image 20251001124149.png]]

De este modo cada usuario ya tendrá acceso a su carpeta vía FTP para subir y bajar archivos.


## **4.2 Acceso a la carpeta WEB de Apache2**  

Dado que nuestro objetivo es montar un servidor web, debemos tener la opción de poder  
acceder a las carpetas de de apache html, para poder transmitir los archivos de una web,  
imagenes, etc.  

Existe varios modos, veamos el que considero mas sencillo para inicial. Que consiste en crear  
un usuario donde su carpeta personal sea la www de Apache2 y dar permisos a esta carpeta. 

Creamos un usuario, por ejemplo uno llamado ftpuser  

``sudo useradd -m ftpuser `` 

![[Pasted image 20251001133450.png]]

![[Pasted image 20251001133634.png]]

![[Pasted image 20251001133527.png]]

Con la opción -m indicamos que nos cree una carpeta para el usuario  

Luego indicamos un password para el usuario.  

``sudo passwd ftpuser``

![[Pasted image 20251001134013.png]]

Finalmente, podemos editar el archivo /etc/passwd para cambiar la carpeta al que el usuario  
tendrá acceso

``sudo nano /etc/passwd`` <- Aquí cambiamos la carpeta del usuario.

![[Pasted image 20251001134248.png]]

Ahí vemos la carpeta por defecto de nuestro usuario, la cambiaremos /var/www/html para poder trabajar ahí directamente.

![[Pasted image 20251001134410.png]]

También podrías jugar con los grupos de usuarios en Linux, para dar acceso a diferentes  
usuarios, algunos comandos útiles que podrías repasar podrían ser:

````
sudo chgrp -R ftpuser www  
sudo groupadd grupo  
chgrp desarrollo algo.txt  
sudo adduser usuario grupo  
cat /etc/group  
chown usuario ruta
````

Al terminar no te olvides de dar permisos a la carpeta de lectura y ejecicón para el resto de  
usuarios como el propio apache.

``sudo chmod -R 775 www``

![[Pasted image 20251001135343.png]]

Y comprobamos:

![[Pasted image 20251001135303.png]]

[⬆️ Volver al índice](#índice)
# 5. Instalación del Intérprete PHP

Nuestro servidor web queremos que funcione con aplicaciones PHP, por lo que debemos  
instalar el interprete y las librerías necesarias para hacerlo funcional.  

En nuestro caso vamos a empezar por instalar el propio interprete y las librerías para mysql y  
para poder ejecutar script de php en termial de linux.  

`sudo apt install php libapache2-mod-php php-mysql php-cli`

**php** : el propio interprete, si queremos una versión especifica podríamos poner php8.1, php7,  
etc.  
**libapache2-mod-php:** Librería que permite trabajar a apache2 con php (Imprescindible en  
nuestro caso)  
**php-mysql:** Librería que permite hacer conexiones desde php a mysql (Imprescindible si  
hacemos app con acceso a bases de datos).  
**php-cli:** Aunque para un servidor web no es necesario, si es util para ejecutar scripts de php  
directamente en la terminal, para temas de mantenimiento, tareas crontab, envió de mensajes,  
etc.

![[Pasted image 20251009192912.png]]

Una vez instalado puede comprobar la versión instalada con:

`php -v`  

![[Pasted image 20251009193102.png]]

Otra prueba interesante que podemos realizar es crear un archivo con extensión php en la  
carpeta de /var/www/html  

Ejemplo:  

`sudo nano /var/www/html/version.php`  

![[Pasted image 20251009193506.png]]

y dentro del archivo escribimos:  

<?php  
phpinfo();  
?>

![[Pasted image 20251009193430.png]]

No te olvides de darle permisos al archivo desde la terminal con: sudo chmod 775 version.php  

![[Pasted image 20251009193735.png]]

Ahora desde el explorador del host o del cliente accedemos al archivo, poniendo la ip de  
nuestro servidor y la llamada al archivo version.php  

![[Pasted image 20251009193905.png]]

Siguieres probar la ejecución por la terminal, puedes crear un archivo con alguna instrucción,  
por ejemplo desde tu propia carpeta de usuario crea un archivo que se llame infosys.php

![[Pasted image 20251009200819.png]]

Ahora en el archivo pon el siguiente fragmento de código PHP:

![[Pasted image 20251009200633.png]]

Una vez guardado, ya puedes ejecutarlo:

![[Pasted image 20251009200949.png]]
[⬆️ Volver al índice](#índice)
# 6. Acceso por SSH desde un IDE - VSCode

Aunque no es un paso necesario para la implantación de un servidor, es interesante poder  
acceder al mismo desde un IDE (Entorno de desarrollo integrado), dado la cantidad de  
asistentes y ayudas de que disponen este tipo de programas.  

En este caso, vamos a utilizar VSCODE, aunque prácticamente todos los IDE ́s modernos  
disponen de este tipo de opciones.  

El primer paso es instalar el programa Visual Studio Code de Microsoft. Es importante no  
confundirlo con la suite de Visual Studio para desarrollo .NET, asegúrate de que pode "code"  

Enlace: https://code.visualstudio.com/Download  

Una vez instalado, vamos a incluir la extension para acceder vía SSH a un servidor remoto.

![[Pasted image 20251009201253.png]]

Una vez tengamos la extensión instalada en vscode, vamos a crear nuestra primera conexión.  
Le damos a botón de abrir conexión remota (1), en la esquina izquierda inferior y  
posteriormente en el cuadro de opciones seleccionamos, connect to host (observa la figura).

![[Pasted image 20251009201829.png]]

Lo siguiente que nos muestra es si queremos conectarnos a una conexión ya creada o realizar  
una nueva. En nuestro caso le daremos a add New SSH Host.

![[Pasted image 20251009201923.png]]

Nos solicita que pongamos un nombre a la conexión (Sin espacios)

![[Pasted image 20251009202204.png]]

Luego nos pregunta que ubicación guardara el archivo de configuración.  


![[Pasted image 20251009202229.png]]
![[Pasted image 20251009202334.png]]

![[Pasted image 20251009202441.png]]
Y finalmente a que tipo de sistema operativo nos vamos a conectar.  

![[Pasted image 20251009202512.png]]


Ahora solo tenemos que escribir los parámetros, como de momento no tenemos Key de  
acceso y esta solo protegido por contraseña, pondremos los siguientes datos, siendo  
hostname la dirección ip que tenga tu servidor.  

![[Pasted image 20251009203423.png]]


![[Pasted image 20251009203350.png]]

![[Pasted image 20251009203552.png]]

Ya estamos conectados y podemos acceder a los archivos del servidor. Vamos a entrar a uno para comprobar.

![[Pasted image 20251009203718.png]]

![[Pasted image 20251009203839.png]]

¡Y LISTO!

[⬆️ Volver al índice](#índice)