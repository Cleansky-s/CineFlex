<?php

namespace es\ucm\fdi\aw\compras;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;
use es\ucm\fdi\aw\peliculas\Pelicula;

class Compras{
    use MagicProperties;

    public static function crea($idUsuario, $idPelicula, $precio, $fecha=null,$id=null)
    {
        $compra = new Compras($idUsuario,$idPelicula,$precio,$fecha , $id);
        return $compra->guarda();
    }

    public static function buscaPorIdUsuarioPelicula($idUsuario, $idPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM compras WHERE idUsuario=%d AND idPelicula=%d", $idUsuario, $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Compras(
                    $fila['idUsuario'],
                    $fila['idPelicula'],
                    $fila['precio'],
                    $fila['fecha'],
                    $fila['id'],
                );
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function devuelvePeliculasCompradas($idUsuario)
    {
        $result=[];
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT idPelicula FROM compras WHERE idUsuario = %d", $idUsuario);
        $rs = $conn->query($query);
        if ($rs) {
            $peliculas = $rs->fetch_all(MYSQLI_ASSOC);
            foreach($peliculas as $pelicula){
                $aux = Pelicula::buscaPorId($pelicula['idPelicula']);
                $result[] = $aux;
            }
            $rs->free();

            return $result;
        }
        else{
            error_log("Error delvuelve carrito ({$conn->errno}): {$conn->error}");
        }
        
        return false;
    }

    private static function inserta($compras)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO compras(idUsuario, idPelicula, precio) VALUES (%d, %d,%d)"
            , $compras->idUsuario
            , $compras->idPelicula
            , $compras->precio
        );
        if ( $conn->query($query) ) {
            $compras->id = $conn->insert_id;
            $result = $compras;
        } else {
            error_log("Error inserta ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private $id;

    private $idUsuario;

    private $idPelicula;

    private $precio;

    private $fecha;


    private function __construct($idUsuario, $idPelicula, $precio, $fecha = null, $id = null)
    {
        $this->id = $id;
        $this->idPelicula = $idPelicula;
        $this->precio = $precio;
        $this->fecha = $fecha;
        $this->idUsuario = $idUsuario;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdPelicula()
    {
        return $this->idPelicula;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function guarda()
    {
        return self::inserta($this);
    }
}