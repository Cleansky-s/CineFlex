<?php

namespace es\ucm\fdi\aw\comentarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\comentarios\Comentario;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioBorrarComentario extends Formulario {

    private $idComentario;

    public function __construct($idComentario) {
        $idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $urlRedireccion = Aplicacion::getInstance()->buildUrl('/infoPelicula.php', ['id' => $idPelicula]);
        $urlRedireccion .= "#comentarios";
        parent::__construct('formBorrarComentario', ["urlRedireccion" => $urlRedireccion]);
        
        $this->idComentario=$idComentario;
    }

    protected function generaCamposFormulario(&$datos)
    {

        $htmlForm = <<<EOS
        <input type="hidden" name="idComentario" value="{$this->idComentario} "/>
        <input type="submit" value="Borrar" />
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $idComentario = $datos['idComentario'];

        $comentario = Comentario::buscaPorId($this->idComentario);

        if($comentario->idPadre==0) {
            $comentario->softDelete($idComentario);
        }
        else {
            $comentario->borrate();
        }

    }
}