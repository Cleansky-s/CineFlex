<?php

class Mensaje
{
    use MagicProperties;

    public const MAX_SIZE = 140;

    public static function crea($idAutor, $mensaje, $respuestaAMensaje = null)
    {
        $m = new Mensaje($idAutor, $mensaje, date('Y-m-d H:i:s'), $respuestaAMensaje);
        return $m;
    }

    public static function buscaPorMensajePadre($idMensajePadre = null)
    {
        $result = [];

        $conn = BD::getInstance()->getConexionBd();
        $query = 'SELECT * FROM Mensajes M WHERE';
        if ($idMensajePadre) {
            $query = $query . ' M.idMensajePadre = %d';
            $query = sprintf($query, $idMensajePadre);
        } else {
            $query = $query . ' M.idMensajePadre IS NULL';
        }

        $query .= ' ORDER BY M.fechaHora DESC';

        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Mensaje($fila['autor'], $fila['mensaje'], $fila['fechaHora'], $fila['idMensajePadre'], $fila['id']);
            }
            $rs->free();
        }

        return $result;
    }

    /* Sólo se debería de tener este método ya podría implemantar la misma funcionalidad que buscaPorMensajePadre */
    public static function buscaPorMensajePadrePaginado($idMensajePadre = null, $numPorPagina = 0, $numPagina = 0)
    {
        $result = [];

        $conn = BD::getInstance()->getConexionBd();
        $query = 'SELECT * FROM Mensajes M WHERE';
        if ($idMensajePadre) {
            $query = $query . ' M.idMensajePadre = %d';
            $query = sprintf($query, $idMensajePadre);
        } else {
            $query = $query . ' M.idMensajePadre IS NULL';
        }

        $query .= ' ORDER BY M.fechaHora DESC';

        if ($numPorPagina > 0) {
            $query .= " LIMIT $numPorPagina";

            /* XXX NOTA: Este método funciona pero poco eficiente (OFFSET y LIMIT se aplican una vez se ha ejecutado la
             * consulta), lo utilizo por simplicidad. En un entorno real se debe utilizar la cláusula WHERE para "saltar"
             * los elementos que NO interesen y utilizar exclusivamente la cláusula LIMIT
             */
            $offset = $numPagina * ($numPorPagina - 1);
            if ($offset > 0) {
                $query .= " OFFSET $offset";
            }
        }

        $rs = $conn->query($query);
        if ($rs) {
            while ($fila = $rs->fetch_assoc()) {
                $result[] = new Mensaje($fila['autor'], $fila['mensaje'], $fila['fechaHora'], $fila['idMensajePadre'], $fila['id']);
            }
            $rs->free();
        }

        return $result;
    }

    public static function buscaPorContenido($textoMensaje = '', $numPorPagina = 0, $numPagina = 0)
    {
      $result = [];
  
      $conn = BD::getInstance()->getConexionBd();
  
      $query = sprintf("SELECT * FROM Mensajes M WHERE M.mensaje LIKE '%%%s%%'"
        , $conn->real_escape_string($textoMensaje)
      );
  
      $query .= ' ORDER BY M.fechaHora DESC';
  
      if ($numPorPagina > 0) {
        $query .= " LIMIT $numPorPagina";
      
        /* XXX NOTA: Este método funciona pero poco eficiente (OFFSET y LIMIT se aplican una vez se ha ejecutado la
         * consulta), lo utilizo por simplicidad. En un entorno real se debe utilizar la cláusula WHERE para "saltar"
         * los elementos que NO interesen y utilizar exclusivamente la cláusula LIMIT
         */
        $offset = $numPagina * ($numPorPagina - 1);
        if ($offset > 0) {
          $query .= " OFFSET $offset";
        }
      }
  
      $rs = $conn->query($query);
      if ($rs) {
        while($fila = $rs->fetch_assoc()) {
          $result[] = new Mensaje($fila['autor'], $fila['mensaje'], $fila['fechaHora'], $fila['idMensajePadre'], $fila['id']);
        }
        $rs->free();
      }
  
      return $result;
    }

    public static function numMensajes($idMensajePadre = null)
    {
        $result = 0;

        $conn = BD::getInstance()->getConexionBd();
        $query = 'SELECT COUNT(*) FROM Mensajes M';
        if ($idMensajePadre) {
            $query = $query . ' AND M.idMensajePadre = %d';
            $query = sprintf($query, $idMensajePadre);
        } else {
            $query = $query . ' AND M.idMensajePadre IS NULL';
        }

        $rs = $conn->query($query);
        if ($rs) {
            $result = (int) $rs->fetch_row()[0];
            $rs->free();
        }
        return $result;
    }

    public static function buscaPorId($idMensaje)
    {
        $result = null;

        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf('SELECT * FROM Mensajes M WHERE M.id = %d;', $idMensaje);
        $rs = $conn->query($query);
        if ($rs && $rs->num_rows == 1) {
            while ($fila = $rs->fetch_assoc()) {
                $result = new Mensaje($fila['autor'], $fila['mensaje'], $fila['fechaHora'], $fila['idMensajePadre'], $fila['id']);
            }
            $rs->free();
        }
        return $result;
    }

    private static function inserta($mensaje)
    {
        $result = false;

        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf(
            "INSERT INTO Mensajes (autor, mensaje, fechaHora, idMensajePadre) VALUES (%d, '%s', '%s', %s)",
            $mensaje->idAutor,
            $conn->real_escape_string($mensaje->mensaje),
            $conn->real_escape_string($mensaje->fechaYHora),
            !is_null($mensaje->idMensajePadre) ? $mensaje->idMensajePadre : 'null'
        );
        $result = $conn->query($query);
        if ($result) {
            $mensaje->id = $conn->insert_id;
            $result = $mensaje;
        } else {
            error_log($conn->error);
        }

        return $result;
    }

    private static function actualiza($mensaje)
    {
        $result = false;

        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf(
            "UPDATE Mensajes M SET autor = %d, mensaje = '%s', fechaHora = '%s', idMensajePadre = %s WHERE M.id = %d",
            $mensaje->idAutor,
            $conn->real_escape_string($mensaje->mensaje),
            $conn->real_escape_string($mensaje->fechaYHora),
            !is_null($mensaje->idMensajePadre) ? $mensaje->idMensajePadre : 'null',
            $mensaje->id
        );
        $result = $conn->query($query);
        if (!$result) {
            error_log($conn->error);
        } else if ($conn->affected_rows != 1) {
            error_log("Se han actualizado '$conn->affected_rows' !");
        }

        return $result;
    }

    private static function borra($mensaje)
    {
        return self::borraPorId($mensaje->id);
    }

    public static function borraPorId($idMensaje)
    {
        if (!$idMensaje) {
            return false;
        }
        $result = false;

        $conn = BD::getInstance()->getConexionBd();
        $query = sprintf("DELETE FROM Mensajes WHERE id = %d", $idMensaje);
        $result = $conn->query($query);
        if (!$result) {
            error_log($conn->error);
        } else if ($conn->affected_rows != 1) {
            error_log("Se han borrado '$conn->affected_rows' !");
        }

        return $result;
    }

    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private $id;

    private $idAutor;

    private $autor;

    private $mensaje;

    private $fechaHora;

    private $idMensajePadre;

    private $mensajePadre;

    private function __construct($idAutor, $mensaje, $fechaHora = null, $idMensajePadre = null, $id = null)
    {
        $this->idAutor = intval($idAutor);
        $this->mensaje = $mensaje;
        $this->fechaHora = $fechaHora !== null ? DateTime::createFromFormat(self::DATE_FORMAT, $fechaHora) :  new DateTime();
        $this->idMensajePadre = $idMensajePadre !== null ? intval($idMensajePadre) : null;
        $this->id = $id !== null ? intval($id) : null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdAutor()
    {
        return $this->idAutor;
    }

    public function getAutor()
    {
        if ($this->idAutor) {
            $this->autor = Usuario::buscaPorId($this->idAutor);
        }
        return $this->autor;
    }

    public function setAutor($nuevoAutor)
    {
        $this->autor = $nuevoAutor;
        $this->idAutor = $nuevoAutor->id;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje($nuevoMensaje)
    {
        if (mb_strlen($nuevoMensaje) > self::MAX_SIZE) {
            throw new Exception(sprintf('El mensaje no puede exceder los %d caracteres', self::MAX_SIZE));
        }
        $this->mensaje = $nuevoMensaje;
    }

    public function getFechaYHora()
    {
        return $this->fechaHora?->format(self::DATE_FORMAT);
    }

    public function getMensajePadre()
    {
        if ($this->idMensajePadre) {
            $this->mensajePadre = self::buscaPorId($this->idMensajePadre);
        }
        return $this->mensajePadre;
    }

    public function setMensajePadre($nuevoMensajePadre)
    {
        $this->mensajePadre = $nuevoMensajePadre;
        $this->idMensajePadre = $nuevoMensajePadre->id;
    }

    public function guarda()
    {
        if (!$this->id) {
            self::inserta($this);
        } else {
            self::actualiza($this);
        }

        return $this;
    }
    
    public function borrate()
    {
        if ($this->id !== null) {
            return self::borra($this);
        }
        return false;
    }
}
