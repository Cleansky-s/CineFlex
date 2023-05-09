<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;
use es\ucm\fdi\aw\peliculas\Pelicula;

class Carrito{
    use MagicProperties;

    public static function crea($idUsuario, $idPeliculas = [], $precioTotal) {
        $carrito = new Carrito($idUsuario, $idPeliculas, $precioTotal);
        return $carrito->guarda();
    }

    public static function devuelvePeliculas($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM carrito WHERE id=%id", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = new Carrito($fila['id'], $fila['idPeliculas'], $fila['precioTotal']);
                foreach($result->idPeliculas as $pelicula){
                    $peliculas[] = Pelicula::buscaPorId($pelicula);
                    
                }
            }
            
        }
        
        return $peliculas;
    }
    
    // por modificar
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

    // por modificar
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

    private static function borraPorIdPelicula($carrito, $idPelicula)
    {
        if (!$idPelicula) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM carrito WHERE id = %d AND idPeliculas = %d"
            , $carrito->idUsuario
            , $idPelicula
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
    }

    private $idUsuario;

    private $idPeliculas;

    private $precioTotal;

    private function __construct($idUsuario, $idPeliculas = [], $precioTotal)
    {
        $this->idUsuario = $idUsuario;
        $this->idPeliculas = $idPeliculas;
        $this->precioTotal = $precioTotal;
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