<?php

namespace es\ucm\fdi\aw\carrito;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\carrito\Carrito;

class FormularioEliminaElemento extends Formulario {

    private $idPelicula;

    public function __construct($idPelicula) {
        $this->idPelicula = $idPelicula;
        parent::__construct('formEliminaElemento', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/carrito.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {   
        
        $htmlForm = <<<EOS
        <input type="submit" value="Eliminar" />
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idUsuario = $app->idUsuario();
        Carrito::eliminaDelCarrito($idUsuario, $this->idPelicula);
    }
}