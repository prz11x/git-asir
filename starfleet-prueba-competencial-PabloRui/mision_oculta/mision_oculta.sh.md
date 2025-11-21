
sudo find / -iname mision_oculta.sh
[sudo] password for pablo2:
/usr/local/bin/mision_oculta.sh


 cat /usr/local/bin/mision_oculta.sh
#!/bin/bash

#Misión oculta de la Flota Estelar
#Solo los cadetes curiosos que exploran el sistema encontrarán este script.
 Sigue las instrucciones que aparecen a continuación.

clear

cat <<'EOF'
========================================================
        M I S I Ó N   O C U L T A   D E   I N G E N I E R Í A
========================================================

Has encontrado un script oculto en el sistema.
Esto significa que te comportas como un verdadero/a admin:
exploras, miras rutas raras y no te quedas solo con lo obvio.

Completa los siguientes pasos y anota las respuestas
en tu informe (README o documento final):

1) SERVICIOS CRÍTICOS
   - Lista el estado de los servicios: apache2, mysql y ufw.

`systemctl status apache2`
`systemctl status mysql`
`systemctl status ufw`

   - Anota si están activos o inactivos y en qué runlevel se inician.

`systemctl get-default`

`systemctl is-enabled apache2`
`systemctl is-enabled mysql`
`systemctl is-enabled ufw`

   - Comando sugerido (no obligatorio): systemctl status NOMBRE_SERVICIO



2) TELEMETRÍA DEL SISTEMA
   - Obtén:
       * la versión del kernel
       
       uname -r

       * el tiempo que lleva encendido el sistema (uptime)
       
     uptime

       * el uso actual de memoria

       free -h

   - Anota en tu informe los comandos que has usado.

3) DOCKER BAJO ESCÁNER
   - Lista todos los contenedores, incluso los detenidos.
   
   -docker ps -a

   - Identifica cuál es el contenedor de WordPress y cuál el de la base de datos.
   
   - Indica:
       * nombre de la imagen
       wp , wp-db
       * estado
       Up About an hour, Up About an hour
       * puertos mapeados (si los hay)
        0.0.0.0:8080->80/tcp, 3306/tcp

4) EXPLORACIÓN DE ARCHIVOS
   - Busca en el sistema un archivo cuyo nombre contenga la palabra "starfleet".
   - Visualiza su contenido y SIGUE LAS INTRUCCIONES INDICADAS.
   - Anota su ruta completa en el informe.
   - Pista: puedes usar el comando 'grep'.
   
      -sudo find / -iname "*starfleet*"
[sudo] password for pablo2:
/home/MISION_STARFLEET_OPS_PROTOCOL_47-A
/home/pablo2/git-asir/starfleet-prueba-competencial-PabloRui
/home/pablo2/git-asir/starfleet-prueba-competencial-PabloRui/Mision_A43/starfleet-web
/home/pablo2/git-asir/starfleet-prueba-competencial-PabloRui/Mision_A43/mensaje_starfleet_A43.md
/opt/enterprise/mensaje_starfleet_A43
/starfleet-prueba-competencial-PabloRui



5) CREAR UN SCRIPT PARA EL CAPITAN

-  El capitan Kirk nos ha pedido que realicemos un script  donde  vea un menú por pantalla para mostrar los puntos 1, 2 , 3 y 4 de este documento, segun el número..

#!/bin/bash

while true; do
    clear
    echo " === PANEL DEL CAPITÁN KIRK === "
    **echo "1) Servicios críticos"**
    **echo "2) Telemetría del sistema"**
    **echo "3) Docker bajo escáner"**
    **echo "4) Exploración de archivos"**
    **echo "5) Salir"**
    **read -p "Selecciona una opción: " op**

    case $op in
        1) 
            systemctl status apache2
            systemctl status mysql
            systemctl status ufw
            read -p "ENTER para continuar..."
            ;;
        2)
            uname -r
            uptime
            free -h
            read -p "ENTER para continuar..."
            ;;
        3)
            docker ps -a
            read -p "ENTER para continuar..."
            ;;
        4)
             sudo find / -iname "*starfleet*"
            read -p "ENTER para continuar..."
            ;;
        5)
            exit 0
            ;;
        *)
            echo "Opción inválida"
            sleep 1
            ;;
    esac
done

Damos permisos:

chmod +x mision.sh



-  Scoty el jefe de ingenieros quiere que le hagas el mismo script, pero sin menu, ejemplo  nombredelscrript 2, mostraria la opcion 2

`#!/bin/bash`

`case $1 in`
    `1)`
        `systemctl status apache2`
        `systemctl status mysql`
        `systemctl status ufw`
        `;;`
    `2)`
        `uname -r`
        `uptime`
        `free -h`
        `;;`
    `3)`
        `docker ps -a`
        `;;`
    `4)`
         `sudo find / -iname "*starfleet*"`
        `;;`
    `*)`
        `echo "Uso: $0 {1|2|3|4}"`
        `;;`
`esac`

damos permisos

`chmod a+x script_sin_menu`

6) PREGUNTA DE REFLEXIÓN
   - Explica brevemente (5-10 líneas) en tu informe:
       "¿Por qué es importante explorar el sistema más allá
        de lo que te dicen las instrucciones de clase?"
        
        Saber como esta organizado el sistema, saber moverte en el, entender como esta dividido el sistema y como funciona es muy importante para realizar cualquier tarea.
        Si no sabes como funciona y como se organiza tu sistema no podrás trabajar en el adecuadamente. 





--------------------------------------------------------
INSTRUCCIONES FINALES:

  - No modifiques este script.
  - No borres ninguna evidencia de lo que has encontrado.
  - Simplemente recoge la información y añádela a tu informe final.

Si has llegado hasta aquí y entiendes todo lo que se pide,
vas por muy buen camino para pensar como administrador/a real.


Añade tu informe al repositorio de Github
========================================================
EOF