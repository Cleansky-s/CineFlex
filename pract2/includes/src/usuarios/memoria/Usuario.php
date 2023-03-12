<?php

class Usuario
{
    const BD_SESS_KEY = 'USUARIOS_BD';

    static function init()
    {
        /* XXX esto es un apaño para poder hacer una demo. El almacenamiento de datos se debe de hacer
         * en una BD.
         */
        $bd = $_SESSION[self::BD_SESS_KEY] ?? null;
        if ($bd == null) {
            $bd = [
                'admin' => new Usuario('admin', password_hash('adminpass', PASSWORD_DEFAULT), 'Administrador', [self::ADMIN_ROLE], 1),
                'user' => new Usuario('user', password_hash('userpass', PASSWORD_DEFAULT), 'Usuario', [self::USER_ROLE], 2)
            ];
            $_SESSION[self::BD_SESS_KEY] = $bd;
        }
    }

    use MagicProperties;

    public const ADMIN_ROLE = 1;

    public const USER_ROLE = 2;

    public static function login($nombreUsuario, $password)
    {
        $usuario = self::buscaUsuario($nombreUsuario);
        if ($usuario && $usuario->compruebaPassword($password)) {
            return $usuario;
        }
        return false;
    }
    
    public static function crea($nombreUsuario, $password, $nombre, $rol)
    {
        $user = new Usuario($nombreUsuario, self::hashPassword($password), $nombre);
        $user->añadeRol($rol);
        return $user->guarda();
    }
    public static function buscaUsuario($nombreUsuario)
    {
        $result = $_SESSION[self::BD_SESS_KEY][$nombreUsuario] ?? false;
        return $result;
    }

    public static function buscaPorId($idUsuario)
    {
        $result = Arrays::find($_SESSION[self::BD_SESS_KEY], function ($usuario) use ($idUsuario) {
            return $usuario->id == $idUsuario;

        });
        return $_SESSION[self::BD_SESS_KEY][$result];
    }
    
    private static function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
   
    private static function inserta($usuario)
    {
        $usuario->id = count($_SESSION[self::BD_SESS_KEY])+1;
        $_SESSION[self::BD_SESS_KEY][$usuario->nombreUsuario] = $usuario;
        return $usuario;
    }
    
    private static function actualiza($usuario)
    {
        $_SESSION[self::BD_SESS_KEY][$usuario->nombreUsuario] = $usuario;
        return $usuario;
    }
   
    private static function borraUsuario($usuario)
    {
        return self::borraPorId($usuario->id);
    }
    
    private static function borraPorId($idUsuario)
    {
        $key = Arrays::find($_SESSION[self::BD_SESS_KEY], function ($usuario) use ($idUsuario) {
            return $usuario->id == $idUsuario;
        });
        if ($key !== false) {
            unset($_SESSION[self::BD_SESS_KEY][$key]);
            return true;
        }
        return false;
    }

    private $id;

    private $nombreUsuario;

    private $password;

    private $nombre;

    private $roles;

    private function __construct($nombreUsuario, $password, $nombre, $id = null, $roles = [])
    {
        $this->id = $id;
        $this->nombreUsuario = $nombreUsuario;
        $this->password = $password;
        $this->nombre = $nombre;
        $this->roles = $roles;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombreUsuario()
    {
        return $this->nombreUsuario;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function añadeRol($role)
    {
        $this->roles[] = $role;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function tieneRol($role)
    {
        return array_search($role, $this->roles);
    }

    public function compruebaPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function cambiaPassword($nuevoPassword)
    {
        $this->password = self::hashPassword($nuevoPassword);
    }
    
    public function guarda()
    {
        if ($this->id !== null) {
            return self::actualiza($this);
        }
        return self::inserta($this);
    }
    
    public function borra()
    {
        if ($this->id !== null) {
            return self::borraUsuario($this);
        }
        return false;
    }
}
