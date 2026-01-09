<?php

require "../modelo/ListaFiguras.php";
require "../modelo/Figura.php";
require "../modelo/Bd.php";


$id = intval($_GET['id']);

//borro el elemento de la BD y su foto
$figura = new Figura();
$figura->borrarFigura($id);


//Pido de nuevo la lista de elementos y la envio a AJAX

$lista = new ListaFiguras();
$lista->obtenerElementos();


echo $lista->imprimirFigurasEnBack();