<?php

namespace es\ucm\fdi\aw\compras;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\carrito\Carrito;
use es\ucm\fdi\aw\compras\Compras;

class FormularioCompraCarrito extends Formulario {

    private $precioTotal;

    public function __construct($precioTotal) {
        $this->precioTotal = $precioTotal;
        parent::__construct('formCompraCarrito', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/biblioteca.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();        

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['precioTotal'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales

        <fieldset class="formulario-pelicula">
            <p>Precio Total: {$this->precioTotal}</p>
            <input type="submit" value="Comprar" />
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idUsuario = $app->idUsuario();
        $carrito = Carrito::devuelvePeliculasCarrito($idUsuario);

        foreach($carrito as $pelicula) {
            Compras::crea($idUsuario, $pelicula->id, $pelicula->precioCompra);
            Carrito::eliminaDelCarrito($idUsuario, $pelicula->id);
        }

    }
}