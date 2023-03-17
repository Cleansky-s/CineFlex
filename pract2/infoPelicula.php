<?php 
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';
require_once 'includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Pelicula';

$idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$pelicula = Pelicula::buscaPorId($idPelicula);
if(!$pelicula){
    Utils::paginaError(403, 'La pelicula no existe');
}

$contenidoPrincipal= detallesPelicula($pelicula);

require 'includes/vistas/comun/layout.php';