<?php 
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Ver Pelicula';

$app->verificaLogado($app->buildUrl('login.php'));

$idPelicula = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

if(!$idPelicula){
    $app->paginaError(403, 'La pelicula no existe');
}

$contenidoPrincipal = verPelicula($idPelicula);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);