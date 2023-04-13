<?php

use es\ucm\fdi\aw\peliculas\Pelicula;
use es\ucm\fdi\aw\valoraciones\FormularioValoracion;


require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/peliculas.php';
require_once __DIR__.'/includes/vistas/helpers/comentarios.php';
//require_once __DIR__.'/includes/vistas/helpers/valoraciones.php';

$tituloPagina = 'Pelicula';

$idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$pelicula = Pelicula::buscaPorId($idPelicula);
if(!$pelicula){
    $app->paginaError(403, 'La pelicula no existe');
}

$contenidoPrincipal = detallesPelicula($pelicula);
//$contenidoPrincipal .= seccionValoraciones($idPelicula);
$contenidoPrincipal .= seccionComentarios($idPelicula);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);