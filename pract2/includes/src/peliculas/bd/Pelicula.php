<?php

class Pelicula {
    use MagicProperties;

    // Funciones de acceso a la BD

    /**
     * Updatear pelicula
     */

    /**
     * Borrar pelicula (nunca) / Update campo 'visible'
     * Soft delete: no se borrara la pelicula de la tabla.
     * Se pondra a false el campo de visible para que no se pueda ver.
     * De esta manera los que tienen la pelicula comprada podran seguir viendola.
     */



    public static function devuelvePeliculas()
    {
        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("SELECT * FROM peliculas LIMIT 20");
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
                $pelicula->cargaGeneros();
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
                $result->cargaGeneros();
            }
            $rs->free();
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private function cargaGeneros()
    {
        
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
        $sql = `
        "UPDATE peliculas SET 
            idProveedor = %d , 
            titulo = '%s' , 
            descripcion = '%s' , 
            urlPortada = '%s' , 
            urlTrailer = '%s' , 
            urlPelicula = '%s' , 
            precioCompra = %s , 
            precioAlquiler = %s , 
            enSuscripcion = %s , 
            fechaCreacion '%s', 
            visible = %s 
        WHERE id = %d" `;
        $query=sprintf( $sql
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
            $result = self::insertaGeneros($pelicula);
            // $result = self::insertaActores($pelicula);
            // $result = self::insertaDirectores($pelicula);
        } else {
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }
    /**
     * Buscar por titulo
     * WHERE titulo LIKE '%'. $titulo .'%'
     */

    /**
     * Buscar por id de proveedor
     */

    /**
     * Buscar por genero
     */

    /**
     * Filtrar por fecha 
     * Ejemplo: WHERE fechaCreacion BETWEEN '2018-01-01' AND '2022-01-01'
     */

    /**
     * Ordenar por valoracion media
     * (opcional, seria concatenar ORDER BY)
     */
    

    /**
     * Insertar pelicula
     */
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
            error_log("Error BD ({$conn->errno}): {$conn->error}");
        }
        return $result;
    }

    private static function insertaGeneros($pelicula)
    {
        $conn = BD::getInstance()->getConexionBd();
        foreach($pelicula->generos as $genero) {
            $query = sprintf("INSERT INTO generospelicula(id, genero) VALUES (%d, '%s')"
                , $pelicula->id
                , $conn->real_escape_string($genero)
            );
            if ( ! $conn->query($query) ) {
                error_log("Error BD ({$conn->errno}): {$conn->error}");
                return false;
            }
        }
        return $pelicula;
    }

    // Datos del objeto

    private const DATE_FORMAT = 'Y-m-d';

    private $id;

    private $proveedor;

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

    public function getProveedor() {
        if($this->idProveedor) {
            $this->proveedor = Usuario::buscaPorId($this->idProveedor);
        }
        return $this->proveedor;
    }

    // Posible no uso de la funcion: cuando se añade una pelicula en principio lo hace un proveedor, por lo que no habria que cambiarla?
    // Unico caso que se me ocurre es que cambie la cuenta/id del proveedor o que un administrador haya añadido una pelicula sin proveedor y luego pase a pertenecer a un proveedor.
    // Nuevo proveedor es un Usuario.
    public function setProveedor($nuevoProveedor) {
        $this->proveedor = $nuevoProveedor;
        $this->idProveedor = $nuevoProveedor->id;
    }

    
    
    public function guarda()
    {
        if ($this->id !== null) {
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