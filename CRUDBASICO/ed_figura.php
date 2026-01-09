<?php
//requires
require "includes/protec.php";

require "modelo/Figura.php";
require "modelo/Bd.php";
require "modelo/funciones.php";

if($_SESSION['permiso']<2){
    header('location:index.php');
}

$figura = new Figura();

if(isset($_GET['id']) && !empty($_GET['id'])){

    $id = intval($_GET['id']);
    $figura->obtenerPorId($id);

}

if(isset($_POST) && !empty($_POST)){

    if(!empty($_POST['id'])){
       //Actualizar
        $id = intval($_POST['id']);
        $figura->update($id,$_POST, $_FILES['foto']);
    }else {
        // Insertar
        $figura->insertar($_POST, $_FILES['foto']);
    }
   // header('location:listarFiguras.php');



}






?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <?php
        include "includes/head.php";
    ?>

</head>
<body>
<?php
include "includes/header.php";
include "includes/menu.php";
?>
<section>
<div class="formulario">
    <h1>Formulario de Figura</h1>

    <form name="figura" action="<?php echo $_SERVER['PHP_SELF']?>" method="post" enctype="multipart/form-data">

        <ul>
            <input type="hidden" name="id" value="<?php echo $figura->getId() ?>">
            <li><label>Nombre: </label><input type="text" name="nombre" value="<?php echo $figura->getNombre() ?>"> </li>
            <li><label>Unidades: </label><input type="number" name="unidades" value="<?php echo $figura->getUnidades() ?>"> </li>
            <?php
                $check = "";
                if($figura->getPintada() == 1){
                    $check = "checked";
                }
            ?>

            <li><label>Pintada: </label><input type="checkbox" name="pintada" value="1" <?php echo $check ?> </li>
            <li><label>Precio: </label><input type="text" name="precio" value="<?php echo $figura->getPrecio() ?>"> </li>
            <li><label>Coleccion: </label><input type="number" name="coleccion" value="<?php echo $figura->getCategoria() ?>"> </li>
            <li><label>Foto: </label><input type="file" name="foto"> </li>
            <?php
                if(strlen($figura->getFoto())>0){
                    echo "<li><img src='".$figura->getFoto()."' width='55px'> </li>";
                }

            ?>

            <li><label>Descripción</label><textarea name="descripcion"><?php echo $figura->getDescripcion() ?></textarea></li>
            <li><input type="submit" value="Guardar"></li>
        </ul>

    </form>

    </div>
</section>
<?php

include "includes/footer.php";

?>


</body>
</html>
