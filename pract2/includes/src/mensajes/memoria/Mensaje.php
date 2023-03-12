<?php

class Mensaje
{
    const BD_SESS_KEY = 'MENSAJES_BD';

    static function init()
    {
        /* XXX esto es un apaño para poder hacer una demo. El almacenamiento de datos se debe de hacer
         * en una BD.
         */
        $bd = $_SESSION[self::BD_SESS_KEY] ?? null;
        if ($bd == null) {
            $now = new DateTime();
            $fechasHoras = [
                $now->format('Y-m-d H:i:s'),
                (clone $now)->add(new DateInterval('PT15M'))->format('Y-m-d H:i:s'),
                (clone $now)->add(new DateInterval('P1DT15M'))->format('Y-m-d H:i:s'),
            ];
    
            $bd = [
                new Mensaje(2, 'Otro mensaje',  $fechasHoras[2], null, 3),
                new Mensaje(2, 'Muchas gracias',  $fechasHoras[1], 1, 2),
                new Mensaje(1, 'Bienvenido al foro', $fechasHoras[0], null, 1)
            ];
            $_SESSION[self::BD_SESS_KEY] = $bd;
        }
    }

    use MagicProperties;

    public const MAX_SIZE = 140;

    public static function crea($idAutor, $mensaje, $respuestaAMensaje = null)
    {
        $m = new Mensaje($idAutor, $mensaje, date('Y-m-d H:i:s'), $respuestaAMensaje);
        return $m;
    }

    public static function buscaPorMensajePadre($idMensajePadre = null)
    {
        $result = $_SESSION[self::BD_SESS_KEY];
        if($idMensajePadre != null) {
            $result = array_filter($_SESSION[self::BD_SESS_KEY], function ($mensaje) use ($idMensajePadre) {
                return $mensaje->idMensajePadre == $idMensajePadre;

            });
        }
        return $result;
    }

    /* Sólo se debería de tener este método ya podría implementar la misma funcionalidad que buscaPorMensajePadre */
    public static function buscaPorMensajePadrePaginado($idMensajePadre = null, $numPorPagina = 0, $numPagina = 0)
    {
        $result = self::buscaPorMensajePadre($idMensajePadre);
        if ($numPorPagina > 0 ) {
            $offset = $numPagina * ($numPorPagina - 1);
            $result = array_slice($result, $offset, $numPorPagina);
        }
        return $result;
    }

    public static function buscaPorContenido($textoMensaje = '', $numPorPagina = 0, $numPagina = 0)
    {
        $result = array_filter($_SESSION[self::BD_SESS_KEY], function ($mensaje) use ($textoMensaje) {
            return mb_strpos($mensaje->mensaje, $textoMensaje) !== false;
        });
        if ($numPorPagina > 0 ) {
            $offset = $numPagina * ($numPorPagina - 1);
            $result = array_slice($result, $offset, $numPorPagina);
        }
    }

    public static function numMensajes($idMensajePadre = null)
    {
        $result = count(self::buscaPorMensajePadre($idMensajePadre));
        return $result;
    }

    public static function buscaPorId($idMensaje)
    {
        $result = Arrays::find($_SESSION[self::BD_SESS_KEY], function ($mensaje) use ($idMensaje) {
            return $mensaje->id == $idMensaje;

        });
        return $_SESSION[self::BD_SESS_KEY][$result];
    }

    private static function inserta($mensaje)
    {
        $mensaje->id = count($_SESSION[self::BD_SESS_KEY])+1;
        array_unshift($_SESSION[self::BD_SESS_KEY], $mensaje);
        return $mensaje;
    }

    private static function actualiza($mensaje)
    {
        $idMensaje = $mensaje->id;
        $key = Arrays::find($_SESSION[self::BD_SESS_KEY], function ($mensaje) use ($idMensaje) {
            return $mensaje->id == $idMensaje;

        });
        if($key !== false) {
            $_SESSION[self::BD_SESS_KEY][$key] = $mensaje;
        }
        return $mensaje;
    }

    public static function borra($mensaje)
    {
        return self::borraPorId($mensaje->id);
    }

    public static function borraPorId($idMensaje)
    {
        $result = false;

        $key = Arrays::find($_SESSION[self::BD_SESS_KEY], function ($mensaje) use ($idMensaje) {
            return $mensaje->id == $idMensaje;

        });
        if($key !== false) {
            unset($_SESSION[self::BD_SESS_KEY][$key]);
            $result = true;
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
