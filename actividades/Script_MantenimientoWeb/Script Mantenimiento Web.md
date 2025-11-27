
creamos un script para backups:

#!/bin/bash

#`configuracion` 
`FECHA=$(date +%Y-%m-%d)`
`DESTINO="$HOME/backups/$FECHA"`
`HTML="/var/www/html"`
`DB_NAME="pruebas"`
`DB_USER="root"`
`DB_PASS="Abcd1234"`


`sudo mkdir -p "$DESTINO"`


`sudo tar -czf "$DESTINO/html_backup.tar.gz" "$HTML"`


`sudo mysqldump -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" > "$DESTINO/db_backup.sql"`

`#Para BORRAR COPIAS ANTIGUAS (más de 7 días)`
`find $HOME/backups/* -type d -mtime +7 -exec rm -rf {} \;`


- Una vez creado le otorgamos permisos

sudo chmod a+x backup.sh


Ahora con crontab -e vamos a indicarle cuando queremos que se ejecute el script automáticamente.

crontab -e

0 0 * * *  /home/rui11/backup.sh

- `0 0` → minuto 0, hora 0 (medianoche)
    
- `* * *` → todos los días, todos los meses, cualquier día de la semana

Y este es el resultado que nos daria, se nos crea un una copia de lo que pedimos y en la ruta especificada

rui11@Ubuntu-VM:~$ cd backups/
rui11@Ubuntu-VM:~/backups$ ls
2025-11-27
rui11@Ubuntu-VM:~/backups$ cd 2025-11-27/
rui11@Ubuntu-VM:~/backups/2025-11-27$ ls
db_backup.sql  html_backup.tar.gz


