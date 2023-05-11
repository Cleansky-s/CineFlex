<?php

namespace es\ucm\fdi\aw\carrito;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\carrito\Carrito;

class FormularioAddElemento extends Formulario {

    private $idPelicula;

    public function __construct($idPelicula) {
        $this->idPelicula = $idPelicula;
        parent::__construct('formEliminaElemento', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/carrito.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {   
        
        $htmlForm = <<<EOS
        <input type="hidden" name="idPelicula" value={$this->idPelicula}/>
        <input type="submit" value="Añadir al carrito" />
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $idPelicula = $datos['idPelicula'];
        $app = Aplicacion::getInstance();
        $idUsuario = $app->idUsuario();
        Carrito::insertaAlCarrito($idUsuario, $idPelicula);
    }
}