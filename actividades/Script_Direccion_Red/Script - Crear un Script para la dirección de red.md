Crear un script en bash que muestre un menú en consola para cambiar entre IP estática y dinámica en Ubuntu Server usando Netplan. El script debe editar el archivo de configuración YAML correspondiente y aplicar los cambios. 

Añade esta información a tu Readme.me del servidor y subelo.

````
```

#!/bin/bash

NETPLAN_FILE="/etc/netplan/50-cloud-init.yaml"
INTERFACE="ens33"

menu() {
    echo "===== MENU CAMBIAR IP (NETPLAN) ====="
    echo "1) Usar DHCP (IP dinámica)"
    echo "2) Usar IP estática"
    echo "3) Salir"
    echo "======================================"
    read -p "Opción: " OPCION
}

aplicar_dhcp() {
    sudo bash -c "cat > $NETPLAN_FILE" <<EOF
network:
  version: 2
  ethernets:
    $INTERFACE:
      dhcp4: true
EOF

    echo "Aplicando cambios..."
    sudo netplan apply
    echo "DHCP activado."
}

aplicar_estatica() {
    read -p "IP (ej: 192.168.1.50/24): " IP
    read -p "Gateway: " GW
    read -p "DNS (ej: 8.8.8.8): " DNS

    sudo bash -c "cat > $NETPLAN_FILE" <<EOF
network:
  version: 2
  ethernets:
    $INTERFACE:
      dhcp4: false
      addresses:
        - $IP
      gateway4: $GW
      nameservers:
        addresses:
          - $DNS
EOF

    echo "Aplicando cambios..."
    sudo netplan apply
    echo "IP estática configurada."
}

menu
case $OPCION in
    1) aplicar_dhcp ;;
    2) aplicar_estatica ;;
    3) exit 0 ;;
    *) echo "Opción no válida." ;;
esac

````

Damos permisos y probamos:

sudo chmod a+x Script_Direccion_Red.sh
./Script_Direccion_Red.sh

## Declaración del archivo a editar

`NETPLAN_FILE="/etc/netplan/50-cloud-init.yaml" INTERFACE="ens33"`

Indicamos el archivo YAML que vamos a modificar y la interfaz de red que queremos configurar.

---

## 2️⃣ Menú

`menu() {     echo "1) DHCP"     echo "2) IP estática"     echo "3) Salir"     read -p "Opción: " OPCION }`

Muestra un menú simple para elegir qué tipo de IP queremos usar.

---

## 3️⃣ Función para DHCP

`aplicar_dhcp() {     sudo bash -c "cat > $NETPLAN_FILE" <<EOF network:   version: 2   ethernets:     $INTERFACE:       dhcp4: true EOF     sudo netplan apply     echo "DHCP activado." }`

Sobrescribe el YAML con `dhcp4: true` y aplica los cambios para que la IP se asigne automáticamente.

---

## 4️⃣ Función para IP estática

`aplicar_estatica() {     read -p "IP/CIDR: " IP     read -p "Gateway: " GW     read -p "DNS: " DNS      sudo bash -c "cat > $NETPLAN_FILE" <<EOF network:   version: 2   ethernets:     $INTERFACE:       dhcp4: false       addresses:         - $IP       gateway4: $GW       nameservers:         addresses:           - $DNS EOF     sudo netplan apply     echo "IP estática configurada." }`

Pide los datos de red, sobrescribe el YAML y aplica la configuración estática.

---

## 5️⃣ Ejecutar opción seleccionada

`menu case $OPCION in     1) aplicar_dhcp ;;     2) aplicar_estatica ;;     3) exit 0 ;;     *) echo "Opción no válida." ;; esac`

Llama a la función correspondiente según la opción que el usuario elija.