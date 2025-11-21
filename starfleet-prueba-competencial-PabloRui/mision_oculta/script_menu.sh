#!/bin/bash

while true; do
    clear
    echo "=== PANEL DEL CAPITÁN KIRK ==="
    echo "1) Servicios críticos"
    echo "2) Telemetría del sistema"
    echo "3) Docker bajo escáner"
    echo "4) Exploración de archivos"
    echo "5) Salir"
    read -p "Selecciona una opción: " op

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
