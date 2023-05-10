<?php

namespace es\ucm\fdi\aw\carrito;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\MagicProperties;
use es\ucm\fdi\aw\peliculas\Pelicula;

class Carrito{
    use MagicProperties;

    public static function crea($id, $idPeliculas = [], $precioTotal) {
        $carrito = new Carrito($id, $idPeliculas, $precioTotal);
        $carrito->guarda();
        return $carrito;
    }

    public static function devuelvePeliculasCarrito($idCarrito)
    {
        $result=[];
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT idPelicula FROM carritopelicula WHERE idCarrito = %d", $idCarrito);
        $rs = $conn->query($query);
        if ($rs) {
            $peliculas = $rs->fetch_all(MYSQLI_ASSOC);
            foreach($peliculas as $pelicula){
                $result = Pelicula::buscaPorId($pelicula['idPelicula']);
                
            }
            $rs->free();
            return $result;
        }
        else{
            error_log("Error delvuelvecarrito ({$conn->errno}): {$conn->error}");
        }
        
        return false;
    }

    public static function buscaPorIdCarrito($idCarrito)
    {
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM carrito WHERE id=%d", $idCarrito);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $idPeliculas = [];
                $result = new Carrito(
                    $fila['id'],
                    $idPeliculas,
                    $fila['precioTotal']
                );
                $result->cargaCarritoPeliculas($result);
            }
            $rs->free();
        } else {
            error_log("Error buscarporid ({$conn->errno}): {$conn->error}");
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
    
    private static function inserta($carrito)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO carrito (id, precioTotal) VALUES ( %d, %d )"
            , $carrito->id
            , $carrito->precioTotal
        );
        if ( $conn->query($query) ) {
            $result = true;
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
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

    private static function actualiza($carrito)
    {
        $result = false;
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query=sprintf("UPDATE carrito U SET precioTotal='%d' WHERE U.id=%d"
            , $carrito->precioTotal
            , $carrito->id
        );
        if ( $conn->query($query) ) {
            $result = self::borraPeliculas($carrito);
            if($result){
                $result = self::insertaPeliculas($carrito);
            }
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        
        return $result;
    }

    private static function borraPeliculas($carrito){
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM carritopelicula WHERE idCarrito = %d"
            , $carrito->id
        );
        if ( ! $conn->query($query) ) {
            error_log("Error borraPeliculasCarrito ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $carrito;
    }

    private static function borraPorIdPelicula($carrito, $idPelicula)
    {
        if (!$idPelicula) {
            return false;
        } 
        $conn = Aplicacion::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM carritopelicula WHERE idCarrito = %d AND idPelicula = %d"
            , $carrito->id
            , $idPelicula
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
            return false;
        }
        return true;
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
    
    public function guarda()
    {
        if (Carrito::buscaPorIdCarrito($this->id)) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }
    

}