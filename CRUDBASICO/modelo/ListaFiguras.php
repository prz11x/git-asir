<?php


class ListaFiguras{

    private $lista;
    private $tabla;


    public function __construct(){

        $this->lista = array();
        $this->tabla = "figura";
    }

    public function obtenerElementos($txt = ""){

        $sqlBusca = "";
        if(strlen($txt)>0){
            $sqlBusca = " WHERE nombre LIKE '%".$txt."%'";
        }

        $sql = "SELECT * FROM ".$this->tabla." ".$sqlBusca.";";

        $conexion = new Bd();
        $res = $conexion->consulta($sql);

        while( list($id, $unidades, $nombre, $pintada, $foto, $descripcion, $precio, $coleccion) = mysqli_fetch_array($res) ){

            $fila = new Figura($id, $unidades, $nombre, $pintada, $foto, $descripcion, $precio, $coleccion);
            array_push($this->lista,$fila);
            //$this->lista[] = $fila;

        }

    }


    public function imprimirFigurasEnBack(){

        $html = "<table>";
        $html .= "<tr><th>ID</th>
                        <th>Nombre</th>
                        <th>Unidades</th>
                        <th>PVP</th>
                        <th>Foto</th>
                        <th colspan='3'></th></tr>";
            for($i=0;$i<sizeof($this->lista);$i++){

                $html .= $this->lista[$i]->imprimeteEnTr();
            }
        $html .= "</table>";

            return $html;

    }




}