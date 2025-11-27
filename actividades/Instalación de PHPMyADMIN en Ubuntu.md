
Lo primero que debemos hacer para instalar PHPMyAdmin en nuestro servidor en el cual ya  
hemos instalado, Apache2, mysql y php. Es crear un usuario para dicho acceso.  
Es importante tener en cuenta que este programa nos exigirá como mínimo tener una política  
de contraseñas MEDIUM en MySql. Por lo primero que debemos hacer es entrar en MySql  
desde la terminal y comprobar la política actual

![[Pasted image 20251016093556.png]]

`SHOW VARIABLES LIKE 'validate_password%';`

![[Pasted image 20251016093621.png]]

Si no lo tenemos en MEDIUM. Para cambiar la política podemos usar:

`SET GLOBAL validate_password.policy=MEDIUM;`


**Crear el usuario para PhpMyAdmin**  

Creamos el usuario desde la terminal de MySql como siempre, importante poner que accederá  
solo desde localhost, dado que es una aplicación que estará instalada en el mismo y le  
aportará seguridad.

También que la contraseña que usemos cumpla con la política MEDIUM de Mysql

`CREATE USER 'phpmyadmin'@'localhost' IDENTIFIED BY 'Alumnos_24';`

![[Pasted image 20251016095456.png]]

Asignamos todos los permisos al usuario con ALL PRIVILEGES.

GRANT ALL PRIVILEGES on *.* TO 'phpmyadmin'@'localhost' WITH GRANT OPTION;  
FLUSH PRIVILEGES;

![[Pasted image 20251016100455.png]]

Y por úlitmo.

`FLUSH PRIVILEGES;`

![[Pasted image 20251016101247.png]]

para **recargar las tablas de privilegios** en memoria

IMPORTANTE: Una vez creado no te olvides de reiniciar el servicio de Mysql en el servidor.

`service mysql restart`

![[Pasted image 20251016101709.png]]

**Instalando la aplicación**  

Dado que PHPMYADMIN utiliza muchas dependencias en los paquetes, vamos a actualizar  
tanto los paquetes como sus dependencias, (Esto puede tardar unos minutos).

`sudo apt update && sudo apt-get upgrade`

Y ahora ejecutamos el paquete de instalación mediante el repositorio apt.

`sudo apt install phpmyadmin`

![[Pasted image 20251016103107.png]]

Durante la instalación nos realizará algunas preguntas

El tipo de servidor que estamos utilizando:

![[Pasted image 20251016103149.png]]

En nuestro caso seleccionamos apache dos (con la tecla espacio) y luego con el tabulador nos  
desplazamos y le damos a ok.  

Nos pregunta si queremos utilizar la configuración por defecto, le indicamos que si.  

![[Pasted image 20251016103337.png]]

Y por último le ponemos la contraseña que hemos configurado en el usuario anterior.  
Ahora ya podemos acceder a la aplicación desde el navegador, poniendo el domino o ip del  
mismo y añadiendo /phpmyadmin  
http://ip_servidor/phpmyadmin/

![[Pasted image 20251016103630.png]]

![[Pasted image 20251016114643.png]]
Ahora, si queremos añadir una capa de seguridad a nuestro servidor, podemos desabilitar las  
conexiones remotas en:

`sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf`

Tambien podemos cerrar el puerto 3306 en el firewall para proteger mas el sistema.
  
`sudo ufw deny 3306`