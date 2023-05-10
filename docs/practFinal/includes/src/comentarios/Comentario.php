<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Comentario{
    use MagicProperties;

    public static function crea($idUsuario, $idPelicula, $texto, $idPadre) {
        $comentario = new Comentario($idUsuario, $idPelicula, $texto, $idPadre);
        return $comentario->guarda();
    }

    public static function devolverBasePorIdPelicula($idPelicula){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM comentarios WHERE idPelicula=%d AND idPadre=0 ORDER BY fechaCreacion DESC", $idPelicula);
        $rs = $conn->query($query);
        $result = [];
        if($rs){
            while($fila = $rs->fetch_assoc()){
                $comentario = new Comentario(
                    $fila['idUsuario'],
                    $fila['idPelicula'],
                    $fila['texto'],
                    $fila['idPadre'],
                    $fila['fechaCreacion'],
                    $fila['eliminado'],
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

    public static function buscaPorId($idComentario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM comentarios WHERE id=%d", $idComentario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = 
                new Comentario(
                    $fila['idUsuario'],
                    $fila['idPelicula'], 
                    $fila['texto'], 
                    $fila['idPadre'], 
                    $fila['fechaCreacion'], 
                    $fila['eliminado'], 
                    $fila['id']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function devolverPorIdPadre($idPadre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM comentarios WHERE idPadre=%d ORDER BY fechaCreacion ASC", $idPadre);
        $rs = $conn->query($query);
        $result = [];
        if($rs){
            while($fila = $rs->fetch_assoc()){
                $comentario = new Comentario(
                    $fila['idUsuario'],
                    $fila['idPelicula'],
                    $fila['texto'],
                    $fila['idPadre'],
                    $fila['fechaCreacion'],
                    $fila['eliminado'],
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

    private static function inserta($comentario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO comentarios (idUsuario, idPelicula, texto, idPadre, eliminado) VALUES ( %d, %d, '%s', %d, %d )"
            , $comentario->idUsuario
            , $comentario->idPelicula
            , $conn->real_escape_string($comentario->texto)
            , !is_null($comentario->idPadre) ? $comentario->idPadre : 'null'
            , $comentario->eliminado ? true : false
        );
        if ( $conn->query($query) ) {
            $comentario->id = $conn->insert_id;
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function actualiza($comentario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE comentarios U SET texto='%s' , eliminado=%d WHERE U.id=%d"
            , $conn->real_escape_string($comentario->texto)
            , $comentario->eliminado
            , $comentario->id
        );
        if ( $conn->query($query) ) {
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    public static function softDelete($idComentario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE comentarios SET eliminado=1 WHERE id=%d"
            , $idComentario
        );
        if ( $conn->query($query) ) {
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    private static function borra($comentario)
    {
        return self::borraPorId($comentario->id);
    }

    private static function borraPorId($idComentario)
    {
        if (!$idComentario) {
            return false;
        } 
        /* Los roles se borran en cascada por la FK
         * $result = self::borraRoles($usuario) !== false;
         */
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM comentarios WHERE id = %d"
            , $idComentario
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private $id;

    private $idUsuario;

    private $idPelicula;

    private $texto;

    private $idPadre;

    private $fechaCreacion;

    private $eliminado;

    private function __construct($idUsuario, $idPelicula, $texto, $idPadre, $fechaCreacion=null, $eliminado=false, $id = null)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
        $this->texto = $texto;
        $this->idPadre = $idPadre;
        $this->fechaCreacion = $fechaCreacion;
        $this->eliminado = $eliminado;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    public function getIdPelicula()
    {
        return $this->idPelicula;
    }

    public function getTexto()
    {
        return $this->texto;
    }

    public function getidPadre()
    {
        return $this->idPadre;
    }

    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    public function getEliminado()
    {
        return $this->eliminado;
    }
    
    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }
    
    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }

}