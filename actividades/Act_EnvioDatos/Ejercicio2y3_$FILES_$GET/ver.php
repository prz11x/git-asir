<?php
$img = $_GET['img'] ?? '';
if ($img && file_exists("img/$img")) {
  echo "<img src='img/$img' width='400'>";
} else {
  echo "Imagen no encontrada.";
}
?>

