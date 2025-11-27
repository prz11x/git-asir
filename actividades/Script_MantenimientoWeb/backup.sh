#!/bin/bash

# configuracion 
FECHA=$(date +%Y-%m-%d)
DESTINO="$HOME/backups/$FECHA"
HTML="/var/www/html"
DB_NAME="pruebas"
DB_USER="root"
DB_PASS="Abcd1234"


sudo mkdir -p "$DESTINO"


sudo tar -czf "$DESTINO/html_backup.tar.gz" "$HTML"


sudo mysqldump -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$DESTINO/db_backup.sql"

#Para BORRAR COPIAS ANTIGUAS (más de 7 días)
find $HOME/backups/* -type d -mtime +7 -exec rm -rf {} \;




#Para ejecutarlo a las 0:00 todos los dias en crontab -e: 
#0 0 * * *  /home/rui11/backup.sh
