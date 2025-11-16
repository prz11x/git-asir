<?php
session_start();
if (!isset($_SESSION['pila'])) $_SESSION['pila'] = [];

if (isset($_POST['dato']) && $_POST['dato'] != "" && strlen($_POST['dato']) <= 20) {
  array_push($_SESSION['pila'], $_POST['dato']);
}

if (isset($_POST['destruir'])) array_pop($_SESSION['pila']);
if (isset($_POST['borrarTodo'])) $_SESSION['pila'] = [];
?>
<form method="post">
  Dato: <input type="text" name="dato">
  <input type="submit" value="Mete">
  <input type="submit" name="destruir" value="Quita">
  <input type="submit" name="borrarTodo" value="Destruir">
</form>
<ul>
<?php foreach($_SESSION['pila'] as $dato) echo "<li>$dato</li>"; ?>
</ul>
