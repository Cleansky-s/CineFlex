<?php

namespace es\ucm\fdi\aw\cines;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\cines\Cine;

class FormularioAddCine extends Formulario {

    public function __construct() {
        parent::__construct('fromCine');
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idProveedor = $app->idUsuario();

        $nombre = $datos['nombre'] ?? '';
        $direccion = $datos['direccion'] ?? '';
        

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['titulo', 'descripcion', 'generos', 'precioCompra', 'precioAlquiler', 'fechaCreacion'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="idProveedor" value="{$idProveedor} "/>
        <fieldset class="formulario-pelicula">

            <label for="nombre">nombre</label>
            <input type="text" name="nombre" required value="{$nombre}"/>
            {$erroresCampos['nombre']}


            <label for="direccion">direccion</label>
            <textarea name="direccion" rows=10 columns=100 required >{$direccion}</textarea>
            {$erroresCampos['direccion']}

        EOS;


        // no es posible poner el input file en predeterminado a como lo tenia antes la pelicula...

        $htmlForm .= <<<EOS
                
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $idProveedor = $datos['idProveedor'];

        $nombre = filter_var($datos['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( ! $nombre ) {
            $this->errores['nombre'] = 'Es necesario el nombre.';
        }

        $direccion = filter_var($datos['direccion'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( ! $direccion || mb_strlen($direccion) < 10) {
            $this->errores['titulo'] = 'Añade una direccion valida.';
        }

        
        if (count($this->errores) === 0) {
        
            $cines = Cine::crea($nombre, $idProveedor, $direccion);
                
            $urlRedireccion = \es\ucm\fdi\aw\Aplicacion::getInstance()->buildUrl('/cines/editarCines.php',
                ['id' => $cines->idCines, 'archivos' => 'true']);
            header("Location: {$urlRedireccion}");
            exit();
            
        }
    }
}