<?php


class Figura{

    private $id;
    private $unidades;
    private $nombre;
    private $pintada;
    private $foto;
    private $descripcion;
    private $precio;
    private $categoria;
    private $tabla;
    private $carpetaFotos;

    /**
     * Figura constructor.
     * @param $id
     * @param $unidades
     * @param $nombre
     * @param $pintada
     * @param $foto
     * @param $descripcion
     * @param $precio
     * @param $coleccion
     * @param $tabla
     * @param $carpetaFotos
     */
    public function __construct($id="", $unidades="", $nombre="", $pintada="", $foto="", $descripcion="", $precio="", $coleccion="")
    {
        $this->id = $id;
        $this->unidades = $unidades;
        $this->nombre = $nombre;
        $this->pintada = $pintada;
        $this->foto = $foto;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->categoria = $coleccion;
        $this->tabla = "figura";
        $this->carpetaFotos = "fotos/";
    }


    private function llenar($id, $unidades, $nombre, $pintada, $foto, $descripcion, $precio, $coleccion)
    {
        $this->id = $id;
        $this->unidades = $unidades;
        $this->nombre = $nombre;
        $this->pintada = $pintada;
        $this->foto = $foto;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->categoria = $coleccion;

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
    public function getUnidades()
    {
        return $this->unidades;
    }

    /**
     * @param string $unidades
     */
    public function setUnidades($unidades)
    {
        $this->unidades = $unidades;
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getPintada()
    {
        return $this->pintada;
    }

    /**
     * @param string $pintada
     */
    public function setPintada($pintada)
    {
        $this->pintada = $pintada;
    }

    /**
     * @return string
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param string $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    /**
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return string
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param string $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /**
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param string $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    public function insertar($datos,$foto){

        if(!isset($datos['pintada'])){
            $datos['pintada'] = 0;
        }

        $conexion = new Bd();
        $conexion->insertarElemento($this->tabla,$datos,$this->carpetaFotos,$foto);
    }

    public function update($id, $datos, $foto){

            $conexion = new Bd();
            $conexion->uppdateBD($id, $this->tabla, $datos, $foto, $this->carpetaFotos);
    }




    /**
     * Version larga
     * @param $id
     */
    public function obtenerPorId($id){

        $sql = "SELECT id, unidades, nombre, pintada, foto, descripcion, precio, coleccion FROM ".$this->tabla." WHERE id=".$id;

        $conexion = new Bd();
        $res = $conexion->consulta($sql);
        list($id, $unidades, $nombre, $pintada, $foto, $descripcion, $precio, $coleccion) = mysqli_fetch_array($res);
        /*
        $this->id = $id;
        $this->unidades = $unidades;
        ...
        */
        $this->llenar($id, $unidades, $nombre, $pintada, $foto, $descripcion, $precio, $coleccion);


    }

    public function borrarFigura($id){

        $conexion = new Bd();
        $conexion->borrarFoto($id, $this->tabla,"../".$this->carpetaFotos);
        $sql = "DELETE FROM ".$this->tabla ." WHERE id=".$id;
        $conexion->consulta($sql);

    }


    public function obtencionPorIdVersionCorta($id){

        $sql = "SELECT id, unidades, nombre, pintada, foto, descripcion, precio, coleccion FROM ".$this->tabla." WHERE id=".$id;

        $conexion = new Bd();
        $res = $conexion->consulta($sql);

    }


    /**
     * Método que retorna una fila para la insercion en una tabla de la clase lista.
     * @return string
     */
    public function imprimeteEnTr(){

            $html = "<tr><td>".$this->id."</td>
                        <td>".$this->nombre."</td>
                        <td>".$this->unidades."</td>
                        <td>".$this->precio."</td>
                        <td><img src='".$this->carpetaFotos.$this->foto."'></td>
                        <td><a href='verFigura.php?id=".$this->id."'>Ver</a> </td>";

                     if($_SESSION['permiso']>1) {

                        $html.= "<td ><a href = 'ed_figura.php?id=".$this->id."' > Editar</a > </td >
                        <td ><a href = 'javascript:borrarFigura(".$this->id.")' > Borrar</a > </td >";
                     }

                       $html .= "</tr>";

            return $html;

    }


    public function imprimirEnFicha() {

        $html = "<table border='1'>";

            $html .= "<tr><th>ID</th>
                        <th>Nombre</th>
                        <th>Unidades</th>
                        <th>PVP</th>
                        <th>Foto</th>
                        <th>Descripción</th>
                       </tr>";
            $html .="  <tr><td>".$this->id."</td>
                        <td>".$this->nombre."</td>
                        <td>".$this->unidades."</td>
                        <td>".$this->precio."</td>
                        <td><img src='".$this->carpetaFotos.$this->foto."'></td>
                        <td>".$this->descripcion."></td>
                        </tr></table>";

        return $html;

    }


}