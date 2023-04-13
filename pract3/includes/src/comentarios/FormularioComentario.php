<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\comentarios\Comentario;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioComentario extends Formulario {

    private $idRespuesta;

    public function __construct($idRespuesta=null) {
        $idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $urlRedireccion = Aplicacion::getInstance()->buildUrl('/infoPelicula.php', ['id' => $idPelicula]);
        $urlRedireccion .= "#comentarios";
        parent::__construct('formComentario', ["urlRedireccion" => $urlRedireccion]);
        
        $this->idRespuesta=$idRespuesta;
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $idUsuario = $app->idUsuario();

        $usuario = Usuario::buscaPorId($idUsuario);

        if($this->idRespuesta!=null) {
            $usuarioRespuesta = Usuario::buscaPorId($this->idRespuesta);

            if(!$usuarioRespuesta) {
                $app->paginaError(502, 'Error', 'Oops', 'El usuario de respuesta del comentario no existe.');
            }

            $idPadre = $usuarioRespuesta->idPadre;
            $prefijoRespuesta = "@{$usuarioRespuesta->nombreUsuario}";

            $texto = $datos['texto'] ?? $prefijoRespuesta;
        }
        else {
            $idPadre = null;
            $texto = $datos['texto'] ?? '';
        }


        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['texto'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="idUsuario" value="{$idUsuario} "/>
        <input type="hidden" name="idPelicula" value="{$idPelicula} "/>
        <input type="hidden" name="idPadre" value="{$idPadre} "/>
        <fieldset class="comentario-form">
            <h3>{$usuario->nombreUsuario}</h3>
            <div class="comentario-texto">
                <textarea name="texto" rows="3" columns="120" maxlength="255" placeholder="Introduce tu comentario aqui..." oninput='this.style.height = "";this.style.height = this.scrollHeight + "px"' >{$texto}</textarea>
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
        $idPadre = $datos['idPadre'];

        $texto = filter_var($datos['texto'], FILTER_SANITIZE_SPECIAL_CHARS);

        $texto = filter_var($datos['texto'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( mb_strlen($texto) > 255) {
            $this->errores['texto'] = 'El comentario no puede superar los 255 caracteres';
        }

        if (count($this->errores) === 0) {

            $comentario = Comentario::crea($idUsuario, $idPelicula, $texto, $idPadre);

            if(!$comentario) {
                // en principio nunca se llegaria a esto.
                $this->errores[] = "Algo ha ocurrido.";
            }
        }
    }
}