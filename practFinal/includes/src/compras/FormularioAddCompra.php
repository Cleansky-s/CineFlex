<?php

namespace es\ucm\fdi\aw\compras;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\carrito\Carrito;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\compras\Compras;

class FormularioAddCompra extends Formulario {

    public function __construct() {
        parent::__construct('formAddCompra', ['urlRedireccion' => Aplicacion::getInstance()->resuelve('/biblioteca.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idUsuario = $app->idUsuario();

        $idPelicula = $datos['idPelicula'] ?? $_POST['idPelicula'];
        $precio = $datos['precio'] ?? $_POST['precio'];
        

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['idPelicula'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="idUsuario" value="{$idUsuario}"/>
        <input type="hidden" name="idPelicula" value="{$idPelicula}"/>
        <input type="hidden" name="precio" value="{$precio}"/>
        <fieldset class="formulario-pelicula">
            <input type="submit" value="Comprar" />
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $idUsuario = $datos['idUsuario'];
        $idPelicula = $datos['idPelicula'];
        $precio = $datos['precio'];

        $idUsuario = filter_var($idUsuario, FILTER_SANITIZE_SPECIAL_CHARS);
        $idPelicula = filter_var($idPelicula, FILTER_SANITIZE_SPECIAL_CHARS);

        $pelicula = Pelicula::buscaPorId($idPelicula);
        if(!$pelicula) {
            $this->errores[] = "La pelicula no existe.";
        }
        
        if (count($this->errores) === 0) {
        
            $compra = Compras::crea($idUsuario, $idPelicula, $precio);
            // para asegurar eliminar del carrito en caso de que esté.
            Carrito::eliminaDelCarrito($idUsuario, $idPelicula);

            if(!$compra) {
                $this->errores[] = "Ha ocurrido un error en la compra.";
            }
             
        }
    }
}