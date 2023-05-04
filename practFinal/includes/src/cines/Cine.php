<?php

namespace es\ucm\fdi\aw\cines;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Cine {

    use MagicProperties;

    
    public static function crea(
        $id,
        $nombre,
        $idProveedor,
        $direccion)
    {
        $pelicula = new Cine($id,$nombre,$idProveedor,$direccion);
        return $pelicula->guarda();
    }

    public static function devuelveCine()
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM cines LIMIT 10");
        $rs = $conn->query($query);
        $result = [];
        if($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $cine = new Cine(
                    $fila['id'],
                    $fila['idProveedor'],
                    $fila['nombre'],
                    $fila['direccion']
                );
                $result[] = $cine;
            }
            $rs->free();
        }
        return $result;
    }


    /**
     * Buscar por id
     */
    public static function buscaPorId($idPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM cines WHERE id=%d", $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $generos = [];
                $result = new Cine(
                    $fila['id'],
                    $fila['idProveedor'],
                    $fila['nombre'],
                    $fila['direccion']
                );
            }
            $rs->free();
        } else {
            error_log("Error buscarporid ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    public static function buscaPorNombreExacto($nombre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        // debido al collation que usamos no es necesario utilizar LOWER()
        $query = sprintf("SELECT * FROM cines WHERE nombre LIKE '%s'"
        , $conn->real_escape_string($nombre));

        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = $fila['id'];
            }
            $rs->free();
        } else {
            error_log("Error buscarporid ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaPorIdProveedor($idProveedor)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM cines WHERE idProveedor=%d", $idProveedor);
        $rs = $conn->query($query);
        $result = [];
        if($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $cine = new Cine(
                    $fila['id'],
                    $fila['idProveedor'],
                    $fila['nombre'],
                    $fila['direccion']
                );

                $result[] = $cine;
            }
            $rs->free();
        }
        return $result;
    }

    private static function actualiza($cines)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE peliculas SET idProveedor = %d , nombre = '%s' , direccion = '%s'  WHERE id = %d;"
            , !is_null($cines->idProveedor) ? $cines->idProveedor : 'null'
            , $conn->real_escape_string($cines->nombre)
            , $conn->real_escape_string($cines->direccion)
            , $conn->real_escape_string($cines->urlPortada)
            , $cines->id
        );
        if ( $conn->query($query) ) {
            $cines->id = $conn->insert_id;
        } else {
            error_log("Error Actualiza ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    
    private static function inserta($cines)
    {  
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO cines(idProveedor, nombre, direccion) VALUES (%d, '%s', '%s' )"
            , !is_null($cines->idProveedor) ? $cines->idProveedor : 'null'
            , $conn->real_escape_string($cines->nombre)
            , $conn->real_escape_string($cines->direccion)
        );
        if ( $conn->query($query) ) {
            $cines->id = $conn->insert_id;
        } else {
            error_log("Error inserta ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    // Datos del objeto

    private const DATE_FORMAT = 'Y-m-d';

    private $id;

    private $idProveedor;

    private $nombre;
    
    private $direccion;


    // Los 3 siguientes no estan implementados en la base de datos aun
    // private $clasificacion;

    // private $actores;

    // private $directores;

    private function __construct(
        $id,
        $nombre,
        $idProveedor,
        $direccion
    ) {
        $this->id = $id;
        $this->idProveedor = $idProveedor;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
    }

    public function getId() {
        return $this->id;
    }
    public function getIdProveedor() {
        return $this->idProveedor;
    }
    public function getNombre() {
        return $this->nombre;
    }
    public function getDireccion() {
        return $this->direccion;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setIdProveedor($idProveedor){

        $this->idProveedor = $idProveedor;
    }
    
    public function guarda()
    {
        if ($this->id != null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

}