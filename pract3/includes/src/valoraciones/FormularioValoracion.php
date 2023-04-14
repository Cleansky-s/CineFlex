<?php

namespace es\ucm\fdi\aw\valoraciones;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\valoraciones\Valoracion;

class FormularioValoracion extends Formulario {

    public function __construct() {
        parent::__construct('formPelicula');
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $idUsuario = $app->idUsuario();

        $texto = $datos['texto'] ?? '';


        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['valoracion', 'texto'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="idUsuario" value="{$idUsuario} "/>
        <input type="hidden" name="idPelicula" value="{$idPelicula} "/>
        <fieldset class="valoracion-form">
            <div class="valoracion-select">
                <label for="valoracion">Valoracion:</label>
                <select name="valoracion" id="valoracion" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
                {$erroresCampos['valoracion']}
            </div>
            <div class="valoracion-texto">
                <label for="texto">Texto (opcional):</label>
                <textarea name="texto" rows=3 columns=100 maxlength="255" oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' >{$texto}</textarea>
                {$erroresCampos['texto']}
            </div>

            <input type="submit" value="Submit" />
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $idUsuario = $datos['idUsuario'];
        $idPelicula = $datos['idPelicula'];

        $texto = trim($datos['texto']);
        $texto = filter_var($texto, FILTER_SANITIZE_SPECIAL_CHARS);
        if ( mb_strlen($texto) > 255) {
            $this->errores['texto'] = 'La valoracion no puede superar los 255 caracteres';
        }
        
        $valor = $datos['valoracion'];
        if($valor<1 || $valor>5){
            $this->errores['valoracion'] = "La valoracion es entre 1 y 5.";
        }
       if (count($this->errores) === 0) {
            
            $valoracion = Valoracion::crea($idUsuario, $idPelicula, $valor, $texto);

            if(!$valoracion)  {
                // en principio nunca se llegaria a esto.
                $this->errores[] = "El usuario ya ha introducido una valoracion en esta pelicula.";
            }
        }
    }
}