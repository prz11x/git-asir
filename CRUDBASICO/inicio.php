<?php
require "modelo/Bd.php";
require "modelo/funciones.php";
require "modelo/Usuario.php";
if(isset($_POST) && !empty($_POST)){

    $mail = addslashes($_POST['mail']);
	$pass = addslashes($_POST['pass']);

    $usuario = new Usuario();
    if($usuario->login($mail,$pass)){
       header("location:index.php");
    }else{
        lanzaError("Usuario No Existe");
    }






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
</head>
<body>

<form name="login" action="<?php  echo $_SERVER['PHP_SELF']?>" method="post">

    <ul>
        <li><label>E-mail</label><input type="email" name="mail"></li>
        <li><label>Password</label><input type="password" name="pass"></li>
        <li><input type="submit" value="Entrar"></li>
    </ul>
</form>

</body>
</html>
