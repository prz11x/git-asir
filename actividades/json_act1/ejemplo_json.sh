#!/usr/bin/env bash
file="datos.json"
nombre=$(jq -r '.nombre' "$file")
ciudad=$(jq -r '.direccion.ciudad' "$file")

mapfile -t skills < <(jq -r '.skills[]' "$file")

echo "Nombre: $nombre"
echo "Ciudad: $ciudad"
echo "Skills:"
for s in "${skills[@]}"; do
  echo " - $s"
done
