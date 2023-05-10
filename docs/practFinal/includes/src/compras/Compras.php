<?php

namespace es\ucm\fdi\aw\compras;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Compras{
    use MagicProperties;

    public static function crea($idUsuario, $idPelicula,$precio, $fecha=null,$id=null)
    {
        $user = new Compras($idUsuario,$idPelicula,$precio,$fecha , $id);
        return $user->guarda();
    }

    public static function buscaPorId($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM Usuarios WHERE id=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = [];
        if ($rs) {
            $fila = $rs->fetch_assoc();
            while ($fila = $rs->fetch_assoc()) {
                $compras = new Compras(
                    $fila['idUsuario'],
                    $fila['idPelicula'],
                    $fila['precio'],
                    $fila['fecha'],
                    $fila['id'],
                );

                $result[] = $compras;
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
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