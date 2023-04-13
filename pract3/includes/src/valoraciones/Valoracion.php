<?php

namespace es\ucm\fdi\aw\valoraciones;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Valoracion{
    use MagicProperties;

    public static function crea($idUsuario, $idPelicula, $valoracion, $texto)
    {
        $valoracion = new Valoracion($idUsuario, $idPelicula, $valoracion, $texto);
        return $valoracion->guarda();
    }

    public static function devolverPorIdPelicula($idPelicula){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM valoraciones WHERE idPelicula=%d", $idPelicula);
        $rs = $conn->query($query);
        $result = [];
        if($rs){
            while($fila = $rs->fetch_assoc()){
                $comentario = new Valoracion(
                    $fila['idUsuario'],
                    $fila['idPelicula'],
                    $fila['texto'],
                    $fila['idPadre'],
                    $fila['fechaCreacion'],
                    $fila['id']
                );
                $result[] = $comentario;
            }
            $rs->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function devolverPorIdUsuario($idUsuario){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM valoraciones WHERE idUsuario=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = [];
        if($rs){
            while($fila = $rs->fetch_assoc()){
                $comentario = new Valoracion(
                    $fila['idUsuario'],
                    $fila['idPelicula'],
                    $fila['texto'],
                    $fila['idPadre'],
                    $fila['fechaCreacion'],
                    $fila['id']
                );
                $result[] = $comentario;
            }
            $rs->free();
        }
        else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaPorIdPeliUsuario($idPelicula, $idUsuario )
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM valoraciones WHERE idPelicula=%d AND idUsuario=%d", $idPelicula, $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Valoracion($fila['idUsuario'], $fila['idPelicula'], $fila['valoracion'], $fila['texto'], $fila['fechaCreacion']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function inserta($valoracion)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO valoraciones(idUsuario, idPelicula, valoracion, texto, fechaCreacion) VALUES (%d, %d, %d, '%s', '%s')"
            , $valoracion->idUsuario
            , $valoracion->idPelicula
            , $valoracion->valoracion
            , $conn->real_escape_string($valoracion->texto)
        );
        if ( $conn->query($query) ) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function actualiza($valoracion)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE valoraciones SET texto = '%s' WHERE idUsuario=%d AND idPelicula=%d"
            , $conn->real_escape_string($valoracion->texto)
            , $valoracion->idUsuario
            , $valoracion->idPelicula
        );
        if ( $conn->query($query) ) {
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    private static function borra($valoracion)
    {
        return self::borraPorId($valoracion->idPelicula, $valoracion->idUsuario);
    }

    private static function borraPorId($idPelicula, $idUsuario)
    {
        /* Los roles se borran en cascada por la FK
         * $result = self::borraRoles($usuario) !== false;
         */
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM valoraciones WHERE idPelicula = %d AND idPelicula=%d"
            , $idPelicula, $idUsuario
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private $idUsuario;

    private $idPelicula;

    private $valoracion;

    private $texto;

    private $fechaCreacion;

    private function __construct($idUsuario, $idPelicula, $valoracion, $texto, $fechaCreacion=null)
    {
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
        $this->valoracion = $valoracion;
        $this->texto = $texto;
        $this->fechaCreacion = $fechaCreacion;
    }

    public function getidUsuario()
    {
        return $this->idUsuario;
    }

    public function getIdPelicula()
    {
        return $this->idPelicula;
    }

    public function getValoracion()
    {
        return $this->valoracion;
    }

    public function getTexto()
    {
        return $this->texto;
    }

    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    
    public function guarda()
    {
        return self::inserta($this);
    }
    
    public function borrate()
    {
        return self::borra($this);
    }

}