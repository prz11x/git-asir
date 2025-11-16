<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $archivo = $_FILES['foto'];
  $permitidos = ['image/jpeg', 'image/png'];
  if (!in_array($archivo['type'], $permitidos)) die("Formato no permitido.");
  

  $nombre = "foto_" . time() . ".jpg";
  move_uploaded_file($archivo['tmp_name'], "img/$nombre"); 
  echo "Archivo subido correctamente: <img src='img/$nombre' width='200'>";
}
?>
