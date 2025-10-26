<?php

/*
Funcion para limpiar datos antes de mostrarlos o procesarlos

trim($dato): elimina espacios en blanco al principio y al final
htmlspecialchars(...): convierte caracteres especiales en entidades HTML 
(por ejemplo, < se convierte en &lt;) para evitar inyección de HTML o XSS.

*/

function limpiar($dato) {
  return htmlspecialchars(trim($dato));
}

$datos = [
  'nombre'    => limpiar($_POST['nombre'] ?? ''),
  'edad'      => intval($_POST['edad'] ?? 0),
  'correo'    => limpiar($_POST['correo'] ?? ''),
  'provincia' => limpiar($_POST['provincia'] ?? ''),
  'fecha'     => limpiar($_POST['fecha'] ?? ''),
  'telfijo'   => limpiar($_POST['telfijo'] ?? ''),
  'telmovil'  => limpiar($_POST['telmovil'] ?? ''),
  'hijos'     => $_POST['hijos'] ?? ''
];

$errores = [];

if ($datos['nombre'] === '' || strlen($datos['nombre']) > 50)
  $errores[] = "Nombre inválido";

if ($datos['edad'] < 1 || $datos['edad'] > 99)
  $errores[] = "Edad inválida";

if (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL))
  $errores[] = "Correo inválido";

if ($datos['provincia'] === '')
  $errores[] = "Provincia requerida";

if (!preg_match("/^\d{2}\/\d{2}\/\d{4}$/", $datos['fecha']))
  $errores[] = "Formato de fecha incorrecto";

if ($datos['telmovil'] !== '' && !preg_match("/^\d{9}$/", $datos['telmovil']))
  $errores[] = "Teléfono móvil inválido";

if ($errores) {
  echo "<h3>Errores:</h3><ul>";
  foreach ($errores as $e) echo "<li>$e</li>";
  echo "</ul><a href='formulario.php'>Volver</a>";
  exit;
}

// Mostrar datos
echo "<h2>Datos recibidos:</h2><p>";
foreach ($datos as $campo => $valor) {
  if ($campo !== 'hijos') {
    echo "<strong>" . ucfirst($campo) . ":</strong> $valor<br>";
  }
}
echo "<strong>Hijos:</strong> {$datos['hijos']}</p>";

// Carta personalizada
echo "<h3>Carta personalizada:</h3>";
$nombre = $datos['nombre'];
$edad = $datos['edad'];
$provincia = $datos['provincia'];

if ($edad < 18) {
  echo "Estimado $nombre, no hay ofertas para menores de edad.";
} elseif ($edad >= 20 && $edad <= 30) {
  echo "Estimado $nombre, en $provincia le ofrecemos una TV de 65''.";
} elseif ($edad >= 31 && $edad <= 60) {
  echo "Estimado $nombre, en $provincia le ofrecemos un viaje con gastos pagados.";
} elseif ($edad > 60) {
  echo "Estimado $nombre, le ofrecemos un bono para parques de atracciones.";
}

if ($datos['hijos'] === 'si') {
  echo "<br>Además, ¡tiene un descuento en consolas!";
}
?>
