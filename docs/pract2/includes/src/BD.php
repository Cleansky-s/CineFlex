<?php

/**
 * Funciones de utilidad para acceso a la base de datos.
 */
class BD
{
    private static $instancia = null;

    /**
     * Devuelve una instancia de {@see BD}.
     * 
     * 
     * @return BD Obtiene la única instancia de la <code>BD</code>
     */
    public static function getInstance()
    {
        if (self::$instancia === null) {
            self::$instancia = new self;
        }
        return self::$instancia;
    }

    private $conexion;

    private function __construct()
    {
        $this->conexion = null;
    }

    function getConexionBd()
    {
        if ($this->conexion == null) {
            $conn = new mysqli(BD_HOST, BD_USER, BD_PASS, BD_NAME);
            if ($conn->connect_errno) {
                error_log("Error de conexión a la BD: ({$conn->connect_errno }) {$conn->connect_error}");
                Utils::paginaError(502, 'Error', 'Oops', 'No ha sido posible conectarse a la base de datos.');
            }

            if (!$conn->set_charset("utf8mb4")) {
                error_log("Error al configurar la codificación de la BD: ({$conn->errno }) {$conn->error}");
                Utils::paginaError(502, 'Error', 'Oops', 'No ha sido posible configurar la base de datos.');
            }

            $this->conexion = $conn;

            // Se llamará a cierraConexion() antes de terminar la ejecución del script
            register_shutdown_function(Closure::fromCallable([$this, 'cierraConexion']));
        }
        return $this->conexion;
    }
    
    private function cierraConexion()
    {
        if ($this->conexion != null && !$this->conexion->connect_errno) {
            $this->conexion->close();
        }
    }
    
}
