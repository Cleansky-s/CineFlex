<?php

namespace es\ucm\fdi\aw\cines;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\cines\Cine;

class FormularioUpdateCines extends Formulario {


    public function __construct() {
        parent::__construct('formPelicula');
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idProveedor = $app->idUsuario();

        $idCine = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $cine = Cine::buscaPorId($idCine);

        $nombre = $datos['nombre'] ?? $cine->nombre;
        $direccion = $datos['direccion'] ?? $cine->direccion;

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['nombre', 'direccion'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="idProveedor" value="{$idProveedor} "/>
        <fieldset class="formulario-pelicula">

                <label for="nombre">nombre:</label>
                <input type="text" name="nombre" required value="{$nombre}"/>
                {$erroresCampos['nombre']}


                <label for="direccion">direccion:</label>
                <textarea name="direccion" rows=10 columns=100 required >{$direccion}</textarea>
                {$erroresCampos['direccion']}

        EOS;


        // no es posible poner el input file en predeterminado a como lo tenia antes la pelicula...

        $htmlForm .= <<<EOS

            <input type="submit" value="Continuar" />
            
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $nombre = filter_var($datos['nombre'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( ! $nombre ) {
            $this->errores['nombre'] = 'Es necesario el nombre.';
        }

        $direccion = filter_var($datos['direccion'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( ! $direccion || mb_strlen($direccion) < 10) {
            $this->errores['direccion'] = 'Añade una direccion valida.';
        }


        if (count($this->errores) === 0) {
            $cine = Cine::buscaPorNombreExacto($nombre);
            $idCine = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

            if(!$cine || $cine == $idCine) {

                $cine = Cine::buscaPorId($idCine);

                $cine->nombre = $nombre;
                $cine->direccion = $direccion;

                $cine->guarda();

                $urlRedireccion = \es\ucm\fdi\aw\Aplicacion::getInstance()->buildUrl('/cines/editarCines.php',
                    ['id' => $idCine ]);
                header("Location: {$urlRedireccion}");
                exit();
            }
            else {
                $this->errores[] = "El cine ya existe.";
            }
        }
    }
}
