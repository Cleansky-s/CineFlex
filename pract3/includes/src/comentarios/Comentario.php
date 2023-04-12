<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Comentario{
    use MagicProperties;

    public static function devolverBasePorIdPelicula($idPelicula){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM comentario WHERE idPelicula=%d AND idPadre IS NULL", $idPelicula);
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
                    $fila['id']
                );
                $result[] = $comentario;
            }
            $rs->free();
        }
        return $result;
    }

    public static function buscaPorId($idComentario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM comentario WHERE id=%d", $idComentario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Comentario($fila['id'], $fila['idUsuario'], $fila['idPelicula'], $fila['texto'], $fila['idPadre'], $fila['fechaCreacion'], $fila['eliminado']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    public static function buscaPorIdPadre($idPadre)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM comentario WHERE id=%d ORDER BY fechaCreacion ASC", $idPadre);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Comentario($fila['id'], $fila['idUsuario'], $fila['idPelicula'], $fila['texto'], $fila['idPadre'], $fila['fechaCreacion'], $fila['eliminado']);
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function inserta($comentario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO comentarios(id, idUsuario, idPelicula, texto, idPadre, fechaCreacion, eliminado) VALUES ('%d', '%d', '%d', '%s', '%d', '%s', '%d')"
            , !is_null($comentario->id) ? $comentario->id : 'null'
            , $conn->real_escape_string($comentario->idUsuario)
            , $conn->real_escape_string($comentario->idPelicula)
            , $conn->real_escape_string($comentario->texto)
            , !is_null($comentario->idPadre) ? $comentario->idPadre : 'null'
            , $conn->real_escape_string($comentario->fechaCreacion)
            , $conn->real_escape_string($comentario->eliminado)
        );
        if ( $conn->query($query) ) {
            $comentario->id = $conn->insert_id;
            $resul = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function actualiza($comentario)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE comentarios U SET eliminado = '%s' WHERE U.id=%d"
            , $conn->real_escape_string($comentario->eliminado)
            , $comentario->id
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
        $query = sprintf("DELETE FROM comentarios U WHERE U.id = %d"
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

    private function __construct($idUsuario, $idPelicula, $texto, $idPadre, $fechaCreacion, $id = null)
    {
        $this->id = $id;
        $this->idUsuario = $idUsuario;
        $this->idPelicula = $idPelicula;
        $this->texto = $texto;
        $this->idPadre = $idPadre;
        $this->fechaCreacion = $fechaCreacion;
        $this->eliminado = '0';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getidUsuario()
    {
        return $this->idUsuario;
    }

    public function getidPelicula()
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