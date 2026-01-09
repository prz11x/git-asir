<?php

require_once "Bd.php"; // AÑADE ESTA LÍNEA

class Usuario{

    private $id;
    private $mail;
    private $pass;
    private $permiso;
    private $tabla;

    /**
     * Usuario constructor.
     * @param $id
     * @param $mail
     * @param $pass
     * @param $permiso
     */
    public function __construct($id="", $mail="", $permiso="",$pass=""){
        $this->id = $id;
        $this->mail = $mail;
        $this->pass = $pass;
        $this->permiso = $permiso;
        $this->tabla = "usuarios";
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param string $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @return string
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * @param string $permiso
     */
    public function setPermiso($permiso)
    {
        $this->permiso = $permiso;
    }


  public function login($mail, $pass){

        $conexion = new Bd();
        $sql = "SELECT id, mail, permiso FROM ".$this->tabla.
               " WHERE mail='".$mail."' AND pass='".md5($pass)."';";
        
$res = $conexion->consulta($sql);
        $conexion->numeroElementos();
        if($conexion->numeroElementos()>0){
            list($id, $maildb, $permiso) = mysqli_fetch_array($res);
            session_start();
            $_SESSION['id_usuario'] = $id;
            $_SESSION['nombre'] = $maildb;
            $_SESSION['permiso'] = $permiso;
            $_SESSION['mail'] = $maildb;
            $respuesta = true;
        }else{
            $respuesta = false;
        }

       return $respuesta;
    }

}
    