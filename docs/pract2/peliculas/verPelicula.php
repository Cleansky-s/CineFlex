<?php 
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Ver Pelicula';

verificaLogado(Utils::buildUrl("login.php"));

$idPelicula = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

if(!$idPelicula){
    Utils::paginaError(403, 'La pelicula no existe');
}

$contenidoPrincipal = verPelicula($idPelicula);

require '../includes/vistas/comun/layout.php';