<?php
header('Content-Type: text/html');

// Obtener estado del firewall
$ufw_status = shell_exec("sudo ufw status");
$ufw_status = trim($ufw_status);

// Mostrarlo en un panel sencillo
echo "<h2>Estado del Escudo (UFW)</h2>";
echo "<pre>$ufw_status</pre>";
?>
