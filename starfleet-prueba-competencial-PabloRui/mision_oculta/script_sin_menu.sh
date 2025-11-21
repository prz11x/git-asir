#!/bin/bash

case $1 in
    1)
        systemctl status apache2
        systemctl status mysql
        systemctl status ufw
        ;;
    2)
        uname -r
        uptime
        free -h
        ;;
    3)
        docker ps -a
        ;;
    4)
        sudo find / -iname "*starfleet*"
        ;;
    *)
        echo "Uso: $0 {1|2|3|4}"
        ;;
esac
