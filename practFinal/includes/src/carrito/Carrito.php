<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;

class Carrito{
    use MagicProperties;

    public static function crea($idUsuario, $idPelicula) {
        $carrito = new Carrito($idUsuario, $idPelicula);
        return $carrito->guarda();
    }
    
    private static function inserta($carrito)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO carrito (idUsuario, idPelicula) VALUES ( %d, %d )"
            , $carrito->idUsuario
            , $carrito->idPelicula
        );
        if ( $conn->query($query) ) {
            $carrito->id = $conn->insert_id;
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function actualiza($carrito)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE carrito U SET idPelicula='%d' WHERE U.id=%d"
            , $carrito->idPelicula
            , $carrito->idUsuario
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