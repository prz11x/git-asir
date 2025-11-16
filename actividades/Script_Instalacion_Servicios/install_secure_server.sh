#!/bin/bash

# Este script será parte de mi evaluación y tiene que quedar muy pro

while true; do
clear
echo "-------------------------------------------------------"
echo "----------- MENU INSTALACIÓN DE SERVICIOS -------------"
echo "-------------------------------------------------------"
echo "1) Instalar SSH"
echo "2) Instalar Apache2"
echo "3) Instalar MYSQL"
echo "4) Instalar PHP"
echo "5) Instalar FTP"
echo "6) Copia seguridad WEB"
echo "7) Copia Bases de Datos"
echo "8) Actualizar repositorio Linux"
echo "9) Apagar equipo"
echo "10) Instalar y configurar GIT"
echo "0) Salir del Script"
echo "-------------------------------------------------------"
read -p "Elige una opción [0-10]: " opcion

case $opcion in
    1)
        echo "Instalando SSH..."
        sudo apt update
        sudo apt install -y openssh-server
        sudo systemctl enable ssh
        sudo systemctl start ssh
        echo "Instalación completa"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    2)
        echo "Instalando servidor Web Apache2..."
        sudo apt update
        sudo apt install -y apache2
        sudo systemctl enable apache2
        sudo systemctl start apache2
        echo "Instalación completa"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    3)
        echo "Instalando servidor MYSQL..."
        sudo apt update
        sudo apt install -y mysql-server
        sudo systemctl enable mysql
        sudo systemctl start mysql
        echo "Instalación completa"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    4)
        echo "Instalando PHP..."
        sudo apt update
        sudo apt install -y php libapache2-mod-php php-mysql
        sudo systemctl restart apache2
        echo "Instalación completa"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    5)
        echo "Instalando servidor FTP (vsftpd)..."
        sudo apt update
        sudo apt install -y vsftpd
        sudo systemctl enable vsftpd
        sudo systemctl start vsftpd
        echo "Instalación completa"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    6)
        echo "Realizando copia de seguridad del sitio web..."
        FECHA=$(date +%d-%m-%Y_%H-%M)
        sudo tar -czvf /home/$USER/backup_web_$FECHA.tar.gz /var/www/html 2>/dev/null
        echo "Copia de seguridad creada en /home/$USER/backup_web_$FECHA.tar.gz"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    7)
        echo "Realizando copia de seguridad de bases de datos..."
        FECHA=$(date +%d-%m-%Y_%H-%M)
        mkdir -p /home/$USER/backups_mysql
        sudo mysqldump --all-databases > /home/$USER/backups_mysql/bbdd_$FECHA.sql
        echo "Copia de seguridad creada en /home/$USER/backups_mysql/bbdd_$FECHA.sql"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    8)
        echo "Actualizando repositorio Linux..."
        sudo apt update && sudo apt upgrade -y
        echo "Actualización completa"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    9)
        echo "Apagando el equipo..."
        sudo shutdown now
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    10)
        echo "Instalando GIT..."
        sudo apt update
        sudo apt install -y git
        echo "Configurando usuario de Git..."
        read -p "Introduce tu nombre de usuario: " gituser
        read -p "Introduce tu correo electrónico: " gitemail
        git config --global user.name "$gituser"
        git config --global user.email "$gitemail"
        echo "Git instalado y configurado correctamente"
        echo "Pulsa cualquier tecla para continuar..."
        read
        ;;

    0)
        echo "Bye!!!"
        exit
        ;;

    *)
        echo "Pon una opción correcta"
        sleep 2
        ;;
esac
done
