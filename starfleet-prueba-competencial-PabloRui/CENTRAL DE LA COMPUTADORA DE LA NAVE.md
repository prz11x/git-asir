http://192.168.1.30:8006 
Acceso: alumno 
Alumno1234 

Maquina asignada a tu nombre:
usuario: pablo2
contraseña - hoja de gerogrifico. 
contraseña - Abcd124

ANTES DE EMEPZAR:
Poner IP - el numero escrito en la hoja es la terminación 
192.168.1.60

Como cambiar la IP:

Edita: /etc/netplan/50-cloud-init.yaml 
network: 
version: 2 
ethernets: 
ens18: 
dhcp4: false 
addresses:
- 192.168.1.60/24 
- gateway4: 192.168.1.1 
- nameservers: 
- addresses:
-1.1.1.1
 -8.8.8.8 
-Luego sudo netplan apply y con ip a mira que tengas la ip buena