<?php

namespace es\ucm\fdi\aw\carrito;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;
use es\ucm\fdi\aw\peliculas\Pelicula;

class Carrito{
    use MagicProperties;

    public static function devuelvePeliculasCarrito($idUsuario)
    {
        $result=[];
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT idPelicula FROM carritopelicula WHERE idCarrito = %d", $idUsuario);
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

    public static function buscaPeliPorIdUsuario($idUsuario, $idPelicula)
    {
        $result=[];
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT idPelicula FROM carritopelicula WHERE idCarrito=%d AND idPelicula=%d", $idUsuario, $idPelicula);
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

    public static function precioCarrito($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT SUM(precioCompra) AS precioTotal FROM carritopelicula C LEFT JOIN peliculas P ON C.idPelicula=P.id WHERE C.idCarrito=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $result = $fila['precioTotal'];
            }
            $rs->free();
        }
        return $result;
    }

    public static function insertaAlCarrito($idUsuario, $idPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("INSERT INTO carritopelicula (idCarrito, idPelicula) VALUES ( %d , %d )", $idUsuario, $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
           $result = true;
        }
        return $result;
    }

    public static function eliminaDelCarrito($idUsuario, $idPelicula)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM carritopelicula WHERE idCarrito=%d AND idPelicula=%d", $idUsuario, $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
           $result = true;
        }
        return $result;
    }

    public static function vaciarCarrito($idUsuario)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM carritopelicula WHERE idCarrito=%d", $idUsuario);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
           $result = true;
        }
        return $result;
    }

    private function cargaCarritoPeliculas($carrito){
        $idPeliculas=[];
        
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT idPelicula FROM carritopelicula WHERE idCarrito=%d"
            , $carrito->id
        );
        $rs = $conn->query($query);
        if ($rs) {
            $idPeliculas = $rs->fetch_all(MYSQLI_ASSOC);

            $carrito->idPeliculas = [];
            foreach($idPeliculas as $pelicula) {
                $carrito->idPeliculas[] = $pelicula['idPelicula'];
            }
            $rs->free();
            return $carrito;
        } else {
            error_log("Error cargaCarritoPeliculas ({$conn->errno}): {$conn->error}");
        }
        return false;
    }
    
    private static function insertaPeliculas($carrito){
        if($carrito->idPeliculas){
            $conn = Aplicacion::getInstance()->getConexionBd();
            foreach($carrito->idPeliculas as $pelicula) {
                $query = sprintf("INSERT INTO carritopelicula (idCarrito, idPelicula) VALUES ( %d , %d )"
                    , $carrito->id
                    , $pelicula
                );
                if ( ! $conn->query($query) ) {
                    error_log("Error insertapeliculas ({$conn->errno}): {$conn->error}");
                    return false;
                }
            }
        }
        return $carrito;
    }

    private $id;

    private $precioTotal;

    private $idPeliculas;

    private function __construct($id, $idPeliculas = [], $precioTotal)
    {
        $this->id = $id;
        $this->idPeliculas = $idPeliculas;
        $this->precioTotal = $precioTotal;
    }

    public function getId(){
        return $this->id;
    }

    public function getIdPeliculas() {
        return $this->idPeliculas;
    }

    public function getPrecioTotal(){
        return $this->precioTotal;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setPrecioTotal($precioTotal){
        $this->precioTotal = $precioTotal;
    }
    public function setIdPeliculas($idPeliculas){
        $this->idPeliculas = $idPeliculas;
    }    

}