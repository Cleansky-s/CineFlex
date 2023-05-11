<?php

namespace es\ucm\fdi\aw\carrito;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\carrito\Carrito;

class FormularioVaciarCarrito extends Formulario {


    public function __construct() {
        parent::__construct('formVaciaCarrito', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/carrito.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {   
        
        $htmlForm = <<<EOS
        <input type="submit" value="Vaciar Carrito" />
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idUsuario = $app->idUsuario();
        
        Carrito::vaciarCarrito($idUsuario);

    }
}