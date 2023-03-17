<?php 
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';
require_once 'includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Pelicula';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$pelicula = Pelicula::buscaPorId($id);

if(!$pelicula) {
    Utils::paginaError(418, $tituloPagina, "La pelicula no existe");
}

$contenidoPrincipal= detallesPelicula($idPelicula);

// Funcion en el helper peliculas.php que te muestra la informacion de la pelicula parecido al boceto de la practica 1.
// $contenidoPrincipal .= muestraPelicula($pelicula);

require 'includes/vistas/comun/layout.php';