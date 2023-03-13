<?php

class Pelicula {
    use MagicProperties;

    // Funciones de acceso a la BD

    /**
     * Insertar pelicula
     */

    /**
     * Updatear pelicula
     */

    /**
     * Borrar pelicula (nunca) / Update campo 'visible'
     * Soft delete: no se borrara la pelicula de la tabla.
     * Se pondra a false el campo de visible para que no se pueda ver.
     * De esta manera los que tienen la pelicula comprada podran seguir viendola.
     */

    /**
     * Buscar por id
     */

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

    // Datos del objeto

    private $id;

    private $idProveedor;

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

    private $enSuscripcion;

    private $enCartelera;

    private $precioCompra;

    private $precioAlquiler;

    private $valoracionMedia; // No obligatorio, pero nos ahorramos usar MEAN() en la tabla valoraciones cada vez que hay que refrescar la pelicula

    private $valoracionCuenta;

    private function __construct() {

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

    // mas metodos
}