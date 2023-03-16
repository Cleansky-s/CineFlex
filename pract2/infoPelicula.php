<?php 
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';

$tituloPagina = 'Pelicula';
$idPelicula = $_GET['id'];

$contenidoPrincipal= detallesPelicula($idPelicula);

require 'includes/vistas/comun/layout.php';