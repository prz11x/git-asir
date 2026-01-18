#!/bin/bash

archivo="bash1.txt"

{
echo "Fecha actual: $(date +%Y-%m-%d)"
echo "Hora actual: $(date +%H:%M:%S)"
echo "PATH: $PATH"
echo "Usuario actual: $(whoami)"
echo "Directorio actual: $(pwd)"
} > "$archivo"

cat "$archivo"
