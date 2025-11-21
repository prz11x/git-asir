
 sudo find / -iname "*47-A*",
sudo grep -R "47-A" / 2>/dev/null

cat MISION_STARFLEET_OPS_PROTOCOL_47-A
Academia de la Flota Estelar — División de Ingeniería

La Academia te asigna una última misión antes de graduarte.
Has recibido acceso a una unidad de entrenamiento holodeck dentro de un Servidor Ubuntu Server en un entorno Proxmox.

Tu objetivo es convertir esta unidad en un nodo operativo de la Flota Estelar, siguiendo los protocolos técnicos reales de los ingenieros de naves clase Galaxy.

Cada alumno actuará como Ingeniero Jefe de su propio módulo.



🖖 1. Registro de Entrada — Personalización del Sistema

Al iniciar tu terminal, la Flota quiere saber quién eres.

Debes modificar el mensaje de bienvenida (MOTD) para que muestre:
(sudo nano /etc/motd)

Nombre del cadete

ID del grupo (ej. YT3)

Un mensaje de alerta o saludo en estilo LCARS

Fecha estelar generada dinámicamente (puede ser la fecha normal)

Cuando la VM arranque, deberá parecer que el sistema está entrando en un submódulo de ingeniería de la USS Enterprise NCC-1701-D.


**Añadimos esto en el mensaje de bienvenida:**

 ________________________________________________________________
|  REGISTRO DE ACCESO — FLOTA ESTELAR — PROTOCOLO 47-A           |
|---------------------------------------------------------------|
|  CADETE: Pablo Rui                                            |
|  GRUPO: YT3                                                    |
|                                                               |
|  <<< LCARS ALERT: Acceso autorizado al módulo de ingeniería >>>|
|                                                               |
|  Fecha estelar: 11-11-28654                                    |
|_______________________________________________________________|

está entrando en el submódulo de ingeniería de la USS Enterprise NCC-1701-D...


                           .-=========-.
                           \'-======-'/
                           _|   __   |_
          ________________.-' |  |__|  | '-.________________
         /  ____   ____   \   |  ____  |   /   ____   ____  \
        /  /_  /  / __ \   \  | |    | |  /   / __ \  \_  \  \
       /____/ /__/ /  \_\___\_|_|____|_|_/___/_/  \_\__\_\____\
           /_/  /_/                      \_\  \_\
                USS ENTERPRISE NCC-1701-D




🛠️ 2. Instalación del Núcleo de Servicios — Pila LAMP

Para que el nodo pueda comunicarse con otras estaciones de la Flota, debes instalar y activar:

Apache (Servidor principal)

`sudo apt install apache2`

`apache2 -v`
`Server version: Apache/2.4.58 (Ubuntu)`
`Server built:   2025-08-11T11:10:09`

MySQL / MariaDB (Base de datos del registro estelar)

`sudo apt install mysql-server mysql-client -y`

`sudo systemctl start mysql`
`sudo systemctl enable mysql`
`sudo systemctl status mysql`

`sudo mysql_secure_installation`

`sudo mysql -u root -p`

PHP (Interfaz de análisis)

La configuración debe quedar estable y los servicios operativos como si fuesen módulos LCARS.

`sudo apt install php libapache2-mod-php php-mysql -y`

`php -v`

`sudo nano /var/www/html/info.php`

`<?php`
`phpinfo();`
`?>`

http://192.168.1.60/info.php



🛡️ 3. Activación del Escudo Deflector — Firewall UFW

Antes de que el nodo quede operativo, debes levantar los escudos.

Protocolos mínimos:

Solo deben permitirse:

Canal de comunicaciones principal (SSH)

Canal web (HTTP / HTTPS)

Todo lo demás queda bloqueado

Mostrar en la web un panel con el “estado del escudo”

`sudo apt install ufw -y`

`sudo ufw allow ssh`
`sudo ufw allow http`
`sudo ufw allow https`

`sudo ufw default deny incoming`
`sudo ufw default allow outgoing`

`sudo ufw enable`

`sudo ufw status verbose`

`sudo ufw status numbered`

`sudo nano /var/www/html/escudo.php`

`<?php`
`header('Content-Type: text/html');`

`// Para obtener el estado del firewall`
`$ufw_status = shell_exec("sudo ufw status");`
`$ufw_status = trim($ufw_status);`

`echo "<h2>Estado del Escudo (UFW)</h2>";`
`echo "<pre>$ufw_status</pre>";`
`?>`

> Para que funcione sin pedir contraseña, agrega esta línea en `/etc/sudoers`:

`www-data ALL=(ALL) NOPASSWD: /usr/sbin/ufw status`

http://192.168.1.60/escudo.php

📡 4. Registro de Telemetría — JSON + HTML

Cada estación de la Flota debe emitir telemetría.

Debes generar un archivo JSON que incluya:

Estado de Apache

Estado de MySQL

Versión de PHP

Versión de Docker

Versión del kernel (núcleo del sistema)

Tiempo activo del servidor (Uptime)

Debes crear una interfaz HTML de estilo LCARS que:

Lea el JSON mediante JavaScript

Muestre los datos como paneles de la consola de mando

Sea accesible desde la web principal del host

Este será el “Panel de Diagnóstico de Ingeniería”.

`sudo nano /var/www/html/telemetria.json`

`{`
  `"apache": "activo",`
  `"mysql": "activo",`
  `"php_version": "8.2.12",`
  `"docker_version": "24.0.5",`
  `"kernel_version": "6.5.0-0-generic",`
  `"uptime": "up 3 hours, 12 minutes",`
  `"ufw": "activo"`
`}`

`sudo nano /var/www/html/panel.html`

`<!DOCTYPE html>`
`<html lang="es">`
`<head>`
    `<meta charset="UTF-8">`
    `<title>Panel de Diagnóstico — USS Competencial</title>`
    `<style>`
        `body {`
            `font-family: monospace;`
            `background-color: black;`
            `color: #33FF99;`
            `padding: 20px;`
        `}`
        `h1 { color: #FF9933; }`
        `.panel {`
            `border: 2px solid #FF9933;`
            `padding: 10px;`
            `margin: 10px 0;`
        `}`
    `</style>`
`</head>`
`<body>`
    `<h1>Panel de Diagnóstico de Ingeniería — USS Competencial</h1>`

    `<div class="panel">`
        `<strong>Estado de Apache:</strong> <span id="apache"></span><br>`
        `<strong>Estado de MySQL:</strong> <span id="mysql"></span><br>`
        `<strong>Versión de PHP:</strong> <span id="php_version"></span><br>`
        `<strong>Versión de Docker:</strong> <span id="docker_version"></span><br>`
        `<strong>Versión del kernel:</strong> <span id="kernel_version"></span><br>`
        `<strong>Tiempo activo del servidor:</strong> <span id="uptime"></span><br>`
        `<strong>Estado del Escudo (UFW):</strong> <span id="ufw"></span>`
    `</div>`

    `<script>`
        `fetch('telemetria.json')`
        `.then(response => response.json())`
        `.then(data => {`
            `document.getElementById('apache').textContent = data.apache;`
            `document.getElementById('mysql').textContent = data.mysql;`
            `document.getElementById('php_version').textContent = data.php_version;`
            `document.getElementById('docker_version').textContent = data.docker_version;`
            `document.getElementById('kernel_version').textContent = data.kernel_version;`
            `document.getElementById('uptime').textContent = data.uptime;`
            `document.getElementById('ufw').textContent = data.ufw;`
        `});`
    `</script>`
`</body>`
`</html>`


http://192.168.1.60/panel.html


🚀 5. Registro Estelar — Repositorio GitHub

Cada cadete debe abrir un repositorio con nombre:

starfleet-prueba-competencial-NOMBRE

El repositorio deberá incluir:

JSON

Scripts

HTML estilo LCARS

Configuraciones

Capturas del trabajo

Un README claro, profesional y con formato de informe técnico de la Flota Estelar, incluyendo:

Objetivos de la misión

Procedimientos ejecutados

Capturas

Manual de despliegue

Conclusiones del cadete