<?php

class Pelicula {

    use MagicProperties;

    public const EXTENSIONES_PERMITIDAS_IMG = array('gif', 'jpg', 'jpe', 'jpeg', 'png', 'webp', 'avif');
    public const EXTENSIONES_PERMITIDAS_VIDEO = array(',p4', 'webm' , 'ogg');

    public const GENEROS = [
        1 => 'action',
        2 => 'adventure',
        3 => 'animation',
        4 => 'comedy',
        5 => 'drama',
        6 => 'fantasy',
        7 => 'historical',
        8 => 'horror',
        9 => 'musical',
        10 => 'noir',
        11 => 'romance',
        12 => 'science fiction',
        13 => 'thriller',
        14 => 'western'
    ];

    
    public static function crea(
        $titulo,
        $descripcion,
        $urlPortada,
        $urlTrailer,
        $urlPelicula,
        $enSuscripcion,
        $fechaCreacion,
        $visible = true,
        $precioCompra = null,
        $precioAlquiler = null,
        $valoracionMedia = 0,
        $valoracionCuenta = 0,
        $idProveedor = null,
        $generos = [],
        $fechaAnadir = null,
        $id = null)
    {
        $pelicula = new Pelicula($titulo, $descripcion, $urlPortada, $urlTrailer, $urlPelicula, $enSuscripcion, $fechaCreacion, $visible, $precioCompra, $precioAlquiler, $valoracionMedia, $valoracionCuenta, $idProveedor, $generos, $fechaAnadir, $id);
        return $pelicula->guarda();
    }

    public static function emptyPelicula() {
        return new Pelicula('','',null,null,null,false,'',true,7.99,2.99);
    }

    public static function devuelvePeliculas()
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM peliculas LIMIT 10");
        $rs = $conn->query($query);
        $result = [];
        if($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $generos = [];
                $pelicula = new Pelicula(
                    $fila['titulo'],
                    $fila['descripcion'],
                    $fila['urlPortada'],
                    $fila['urlTrailer'],
                    $fila['urlPelicula'],
                    $fila['enSuscripcion'],
                    $fila['fechaCreacion'],
                    $fila['visible'],
                    $fila['precioCompra'],
                    $fila['precioAlquiler'],
                    $fila['valoracionMedia'],
                    $fila['valoracionCuenta'],
                    $fila['idProveedor'],
                    $generos,
                    $fila['fechaAnadir'],
                    $fila['id']
                );
                $pelicula->cargaGeneros($pelicula);
                $result[] = $pelicula;
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
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM peliculas WHERE id=%d", $idPelicula);
        $rs = $conn->query($query);
        $result = false;
        if ($rs) {
            $fila = $rs->fetch_assoc();
            if ($fila) {
                $generos = [];
                $result = new Pelicula(
                    $fila['titulo'],
                    $fila['descripcion'],
                    $fila['urlPortada'],
                    $fila['urlTrailer'],
                    $fila['urlPelicula'],
                    $fila['enSuscripcion'],
                    $fila['fechaCreacion'],
                    $fila['visible'],
                    $fila['precioCompra'],
                    $fila['precioAlquiler'],
                    $fila['valoracionMedia'],
                    $fila['valoracionCuenta'],
                    $fila['idProveedor'],
                    $generos,
                    $fila['fechaAnadir'],
                    $fila['id']
                );
                $result->cargaGeneros($result);
            }
            $rs->free();
        } else {
            error_log("Error buscarporid ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }


    private function cargaGeneros($pelicula)
    {
        $generos=[];
        
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("SELECT genero FROM generospelicula WHERE id=%d"
            , $pelicula->id
        );
        $rs = $conn->query($query);
        if ($rs) {
            $generos = $rs->fetch_all(MYSQLI_ASSOC);

            $pelicula->generos = [];
            foreach($generos as $genero) {
                $pelicula->generos[] = $genero['genero'];
            }
            $rs->free();
            return $pelicula;
        } else {
            error_log("Error cargageneros ({$conn->errno}): {$conn->error}");
        }
        return false;
    }

    public static function buscaPorIdProveedor($idProveedor)
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM peliculas WHERE idProveedor=%d", $idProveedor);
        $rs = $conn->query($query);
        $result = [];
        if($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $generos = [];
                $pelicula = new Pelicula(
                    $fila['titulo'],
                    $fila['descripcion'],
                    $fila['urlPortada'],
                    $fila['urlTrailer'],
                    $fila['urlPelicula'],
                    $fila['enSuscripcion'],
                    $fila['fechaCreacion'],
                    $fila['visible'],
                    $fila['precioCompra'],
                    $fila['precioAlquiler'],
                    $fila['valoracionMedia'],
                    $fila['valoracionCuenta'],
                    $fila['idProveedor'],
                    $generos,
                    $fila['fechaAnadir'],
                    $fila['id']
                );

                $result[] = $pelicula;
            }
            $rs->free();
        }
        return $result;
    }

    private static function actualiza($pelicula)
    {
        $result = false;
        $conn = BD::getInstance()->getConexionBd();
        $query=sprintf("UPDATE peliculas SET idProveedor = %d , titulo = '%s' , descripcion = '%s' , urlPortada = '%s' , urlTrailer = '%s' , urlPelicula = '%s' , precioCompra = %s , precioAlquiler = %s , enSuscripcion = %s , fechaCreacion = '%s', visible = %s WHERE id = %d;"
            , !is_null($pelicula->idProveedor) ? $pelicula->idProveedor : 'null'
            , $conn->real_escape_string($pelicula->titulo)
            , $conn->real_escape_string($pelicula->descripcion)
            , $conn->real_escape_string($pelicula->urlPortada)
            , $conn->real_escape_string($pelicula->urlTrailer)
            , $conn->real_escape_string($pelicula->urlPelicula)
            , !is_null($pelicula->precioCompra) ? $pelicula->precioCompra : 'DEFAULT'
            , !is_null($pelicula->precioAlquiler) ? $pelicula->precioAlquiler : 'DEFAULT'
            , ($pelicula->enSuscripcion) ? 'TRUE' : 'FALSE'
            , $conn->real_escape_string($pelicula->fechaCreacion)
            , ($pelicula->visible) ? 'TRUE' : 'FALSE'
            , $pelicula->id
        );
        if ( $conn->query($query) ) {
            $pelicula->id = $conn->insert_id;
            $result = self::borraGeneros($pelicula);
            if($result){
                $result = self::insertaGeneros($pelicula);
            }
        } else {
            error_log("Error Actualiza ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    
    private static function borraGeneros($pelicula)
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM generospelicula WHERE id = %d"
            , $pelicula->id
        );
        if ( ! $conn->query($query) ) {
            error_log("Error BorraGeneros ({$conn->errno}): {$conn->error}");
            return false;
        }
        return $pelicula;
    }

    private static function inserta($pelicula)
    {
        $result = false;
        $conn = BD::getInstance()->getConexionBd();
        $query=sprintf("INSERT INTO peliculas(idProveedor, titulo, descripcion, urlPortada, urlTrailer, urlPelicula, precioCompra, precioAlquiler, enSuscripcion, fechaCreacion, visible) VALUES (%d, '%s', '%s', '%s', '%s', '%s', %s , %s , %s , '%s', %s )"
            , !is_null($pelicula->idProveedor) ? $pelicula->idProveedor : 'null'
            , $conn->real_escape_string($pelicula->titulo)
            , $conn->real_escape_string($pelicula->descripcion)
            , $conn->real_escape_string($pelicula->urlPortada)
            , $conn->real_escape_string($pelicula->urlTrailer)
            , $conn->real_escape_string($pelicula->urlPelicula)
            , !is_null($pelicula->precioCompra) ? $pelicula->precioCompra : 'DEFAULT'
            , !is_null($pelicula->precioAlquiler) ? $pelicula->precioAlquiler : 'DEFAULT'
            , ($pelicula->enSuscripcion) ? 'TRUE' : 'FALSE'
            , $conn->real_escape_string($pelicula->fechaCreacion)
            , ($pelicula->visible) ? 'TRUE' : 'FALSE'
        );
        if ( $conn->query($query) ) {
            $pelicula->id = $conn->insert_id;
            $result = self::insertaGeneros($pelicula);
            // $result = self::insertaActores($pelicula);
            // $result = self::insertaDirectores($pelicula);
        } else {
            error_log("Error inserta ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function insertaGeneros($pelicula)
    {
        $conn = BD::getInstance()->getConexionBd();
        foreach($pelicula->generos as $genero) {
            $query = sprintf("INSERT INTO generospelicula (id, genero) VALUES ( %d , %d )"
                , $pelicula->id
                , $genero
            );
            if ( ! $conn->query($query) ) {
                error_log("Error insertageneros ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return $pelicula;
    }

    // Datos del objeto

    private const DATE_FORMAT = 'Y-m-d';

    private $id;

    private $idProveedor;

    private $titulo;
    
    private $descripcion;

    private $generos;

    // Los 3 siguientes no estan implementados en la base de datos aun
    // private $clasificacion;

    // private $actores;

    // private $directores;

    private $urlPortada;

    private $urlTrailer;

    private $urlPelicula;

    private $precioCompra;

    private $precioAlquiler;
    
    private $enSuscripcion;

    private $valoracionMedia; // No obligatorio, pero nos ahorramos usar MEAN() en la tabla valoraciones cada vez que hay que refrescar la pelicula

    private $valoracionCuenta;

    private $fechaCreacion;

    private $visible;

    private $fechaAnadir;

    private function __construct(
        $titulo,
        $descripcion,
        $urlPortada,
        $urlTrailer,
        $urlPelicula,
        $enSuscripcion,
        $fechaCreacion,
        $visible = true,
        $precioCompra = null,
        $precioAlquiler = null,
        $valoracionMedia = 0,
        $valoracionCuenta = 0,
        $idProveedor = null,
        $generos = [],
        $fechaAnadir = null,
        $id = null,
    ) {
        $this->id = $id;
        $this->idProveedor = $idProveedor;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->generos = $generos;
        $this->urlPortada = $urlPortada;
        $this->urlTrailer = $urlTrailer;
        $this->urlPelicula = $urlPelicula;
        $this->precioCompra = $precioCompra;
        $this->precioAlquiler = $precioAlquiler;
        $this->enSuscripcion = $enSuscripcion;
        $this->valoracionMedia = $valoracionMedia;
        $this->valoracionCuenta = $valoracionCuenta;
        $this->fechaCreacion = $fechaCreacion;
        $this->visible = $visible;
        $this->fechaAnadir = $fechaAnadir;
    }

    public function getId() {
        return $this->id;
    }
    public function getIdProveedor() {
        return $this->idProveedor;
    }
    public function getTitulo() {
        return $this->titulo;
    }
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function getUrlPortada() {
        return $this->urlPortada;
    }
    public function getUrlTrailer() {
        return $this->urlTrailer;
    }
    public function getUrlPelicula() {
        return $this->urlPelicula;
    }
    public function getPrecioCompra() {
        return $this->precioCompra;
    }
    public function getPrecioAlquiler() {
        return $this->precioAlquiler;
    }
    public function getEnSuscripcion() {
        return $this->enSuscripcion;
    }
    public function getValoracionMedia() {
        return $this->valoracionMedia;
    }
    public function getValoracionCuenta() {
        return $this->valoracionCuenta;
    }
    public function getFechaCreacion() {
        return $this->fechaCreacion;
    }
    public function getVisible() {
        return $this->visible;
    }
    public function getFechaAnadir() {
        return $this->fechaAnadir;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUrlPortada($url) {
        $this->urlPortada = $url;
    }
    public function setUrlTrailer($url) {
        $this->urlTrailer = $url;
    }
    public function setUrlPelicula($url) {
        $this->urlPelicula = $url;
    }

    public function tieneGenero($genero)
    {
        if ($this->generos == null) {
            self::cargaGeneros($this);
        }
        return array_search($genero, $this->generos) !== false;
    }    

    public function generosToString() 
    {
        $return = '';
        foreach($this->generos as $genero) {
            $return .= ucfirst(Pelicula::GENEROS[$genero]) . " ";
        }
        return $return;
    }
    
    public function guarda()
    {
        if ($this->id != null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }

    public function noVisible()
    {
        if ($this->id !== null || $this->visible == true) {
            $this->visible = false;
            return self::actualiza($this);
        }
        return false;
    }
}