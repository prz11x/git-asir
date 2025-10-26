<?php
$imagenes = scandir("img/");
foreach ($imagenes as $img) {
  if ($img != "." && $img != "..") {
    echo "<a href='ver.php?img=$img'>$img</a><br>";
  }
}
?>
