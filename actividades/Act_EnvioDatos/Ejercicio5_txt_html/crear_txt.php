<?php
$contador = 1;
do {
  $nombreArchivo = "archivo" . $contador . ".txt";
  $contador++;
} while (file_exists($nombreArchivo));

$archivo = fopen($nombreArchivo, "w");
$contenido = "Archivo creado por Pablo Rui.";
fwrite($archivo, $contenido);
fclose($archivo);
echo "Archivo '$nombreArchivo' creado correctamente.";
