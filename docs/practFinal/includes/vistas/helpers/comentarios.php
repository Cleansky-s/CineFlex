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
    if($idUsuario!=''){
        $formResponde = new FormularioComentario($comentario->id);
        $htmlForm = $formResponde->gestiona();
        $idUnico = "respuesta-{$comentario->id}";
        $html .= <<<EOS
        <button onclick="muestraFormularioRespuesta('$idUnico')">Responde</button>
        EOS;
        $html .= "<div id='$idUnico' style='display: none;'>";
        $html .= $htmlForm;
        $html .= "</div>";
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
    <script>
    function muestraFormularioRespuesta(id) {
        var x = document.getElementById(id);
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
    </script>
    <div id="comentarios" class="seccion-comentarios">
    <h2>Comentarios</h2>
    $htmlForm
    EOS;

    /** Decision de hacer los comentarios de 1 nivel, el comentario base y las respuestas.
     * En caso de querer hacer multiples niveles, hacer una funcion recursiva.
     */
    $comentariosBase = Comentario::devolverBasePorIdPelicula($idPelicula, null);

    if(count($comentariosBase) > 0) {
        $html .= "<ul>";
        foreach($comentariosBase as $comentarioBase) {
            $respuestas = seccionRespuestas($comentarioBase->id);
    
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
        $html .= "</ul>";
    }
    else {
        $html .= "<h3>Se el primero en comentar!!</h3>";
    }

    $html .= "</div>";
    return $html;
}

function seccionRespuestas($idPadre) {
    $app = Aplicacion::getInstance();

    $respuestas = Comentario::devolverPorIdPadre($idPadre);
    if(count($respuestas) == 0) {
        return "";
    }

    // hacer que sea expandible
    $html = <<<EOS
        <div class="comentarios-respuesta">
        <ul>
    EOS;

    foreach($respuestas as $respuesta) {
        $html .= "<li>";
        $html .= muestraComentario($respuesta);
        $html .= "</li>";
    }

    $html .= "</ul></div>";

    return $html;
}