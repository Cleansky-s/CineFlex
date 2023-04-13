<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\comentarios\Comentario;
use es\ucm\fdi\aw\comentarios\FormularioBorrarComentario;
use es\ucm\fdi\aw\comentarios\FormularioComentario;
use es\ucm\fdi\aw\usuarios\Usuario;

function muestraComentario($comentario) {

    $app = Aplicacion::getInstance();
    $usuario = Usuario::buscaPorId($comentario->idUsuario);
    $idUsuario = $app->idUsuario();

    $idRespuesta = $comentario->idPadre ?? $comentario->id;

    if($comentario->eliminado==1) {
        return <<<EOS
        <div class="comentario">
        <h3>Eliminado</h3>
        <p>Este comentario ha sido eliminado</p>
        </div>
        EOS;
    }
        

    $html = <<<EOS
        <div class="comentario">
            <h3>{$usuario->nombreUsuario}</h3>
            <p>{$comentario->fechaCreacion}</p>
            <p>{$comentario->texto}</p>
    EOS;
    if($idUsuario == $comentario->idUsuario){
        $formBorra = new FormularioBorrarComentario($comentario->id);
        $htmlForm = $formBorra->gestiona();
        $html .= $htmlForm;
    }
    // hacer un comentario
    

    $html .= "</div>";
    return $html;
}

function seccionComentarios($idPelicula) 
{
    $app = Aplicacion::getInstance();
    $idUsuario = $app->idUsuario();

    $htmlForm = "";
    if($idUsuario!=''){
        $formularioComentario = new FormularioComentario(null);
        $htmlForm .= $formularioComentario->gestiona();
    }
    else {
        $urlLogin = $app->buildUrl('/login.php');
        $htmlForm .= "<a href='{$urlLogin}'>Logeate para comentar</a>";
    }

    $html = <<<EOS
    <div id="comentarios" class="seccion-comentarios">
    <h2>Comentarios</h2>
    $htmlForm
    <ul>
    EOS;

    /** Decision de hacer los comentarios de 1 nivel, el comentario base y las respuestas.
     * En caso de querer hacer multiples niveles, hacer una funcion recursiva.
     */
    $comentariosBase = Comentario::devolverBasePorIdPelicula($idPelicula, null);

    foreach($comentariosBase as $comentarioBase) {
        $respuestas = seccionRespuestas($comentarioBase->idPadre);

        if($comentarioBase->eliminado==1 && $respuestas==""){
            $comentarioBase->borrate();
        }
        else {
            $html .= "<li>";
            $html .= muestraComentario($comentarioBase);
            $html .= $respuestas;
            $html .= "</li>";
        }
    }

    $html .= "</ul></div>";
    return $html;
}

function seccionRespuestas($idPadre) {
    $app = Aplicacion::getInstance();

    $comentariosHijo = Comentario::devolverPorIdPadre($idPadre);
    if(count($comentariosHijo) == 0) {
        return "";
    }

    // hacer que sea expandible
    $html = <<<EOS
        <button class="btn-expandir">Expandir respuestas</button>
        <div class="comentarios-respuesta">
        <ul>
    EOS;

    foreach($comentariosHijo as $respuesta) {
        $html .= "<li>";
        $html .= muestraComentario($respuesta);

        //$html .= seccionRespuestas($comentarioBase);
        $html .= "</li>";
    }

    $html .= "</ul></div>";

    return $html;
}