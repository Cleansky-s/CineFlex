<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\valoraciones\FormularioValoracion;
use es\ucm\fdi\aw\valoraciones\Valoracion;

function seccionValoraciones($idPelicula, $numPagina=1, $numPaginaComentarios=1) {


    $app = Aplicacion::getInstance();
    $idUsuario = $app->idUsuario();

    $valoracionUsuario = Valoracion::buscaPorUsuarioPeli($idUsuario, $idPelicula);

    $htmlForm = "";
    if($idUsuario!=''){
        if(!$valoracionUsuario){
            $formularioValoracion = new FormularioValoracion(null);
            $htmlForm .= $formularioValoracion->gestiona();
        }
    }
    else {
        $urlLogin = $app->buildUrl('/login.php');
        $htmlForm .= "<a href='{$urlLogin}'>Logeate para añadir una valoracion</a>";
    }

    $html = <<<EOS
    <div id="comentarios" class="seccion-comentarios">
    <h2>Valoraciones</h2>
    $htmlForm
    EOS;

    $html .= listaValoracionesPaginado($idPelicula, $numPagina, $numPaginaComentarios);

    $html .= "</div>";
    return $html;
}

function muestraValoracion($valoracion) {

    $usuario = Usuario::buscaPorId($valoracion->idUsuario);

    return <<<EOS
        <div class="comentario">
            <h3>{$usuario->nombreUsuario} {$valoracion->valoracion} estrellas</h3>
            <p>{$valoracion->texto}</p>
    EOS;
}

function listaValoracionesPaginado($idPelicula, $numPagina=1, $numPaginaComentarios=1) {

    $url = "infoPelicula.php";
    $app = Aplicacion::getInstance();

    $numPorPagina = 5;

    $valoraciones = Valoracion::devolverPorIdPeliculaPaginado($idPelicula, $numPagina-1);

    $numValoraciones = count($valoraciones);
    if ($numValoraciones == 0) {
        return "<h3>Se el primero en opinar!</h3>";
    }

    $haySiguientePagina = false;
    if ($numValoraciones > $numPorPagina) {
        $numValoraciones = $numPorPagina;
        $haySiguientePagina = true;
    }

    $html = '<ul>';
    for($i = 0; $i < $numValoraciones; $i++) {
        $valoracion = $valoraciones[$i];
        $html .= '<li>';
        $html .= muestraValoracion($valoracion);
        $html .= '</li>';
    }
    $html .= '</ul>';

    // Controles de paginacion
    $clasesPrevia='deshabilitado';
    $clasesSiguiente = 'deshabilitado';
    $hrefPrevia = '';
    $hrefSiguiente = '';

    if ($numPagina > 1) {
        // Seguro que hay mensajes anteriores
        $paginaPrevia = $numPagina - 1;
        $clasesPrevia = '';
        $hrefPrevia = $app->buildUrl($url, array_merge([
            'id' => $idPelicula,
            'numPaginaValoraciones' => $paginaPrevia,
            'numPaginaComentarios' => $numPaginaComentarios ?? 1
        ]));
    }

    if ($haySiguientePagina) {
        // Puede que haya mensajes posteriores
        $paginaSiguiente = $numPagina + 1;
        $clasesSiguiente = '';
        $hrefPrevia = $app->buildUrl($url, array_merge([
            'id' => $idPelicula,
            'numPaginaValoraciones' => $paginaSiguiente,
            'numPaginaComentarios' => $numPaginaComentarios ?? 1
        ]));
    }

    $html .=<<<EOS
        <div>
            Página: $numPagina, <a class="boton $clasesPrevia" href="$hrefPrevia">Previa</a> <a class="boton $clasesSiguiente" href="$hrefSiguiente">Siguiente</a>
        </div>
    EOS;
    

    return $html;
}