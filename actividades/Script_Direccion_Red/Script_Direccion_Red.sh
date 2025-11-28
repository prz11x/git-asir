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
