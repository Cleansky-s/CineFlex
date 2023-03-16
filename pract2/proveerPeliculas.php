<?php 
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';
require_once 'includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Proveer Peliculas';

if (! esProveedor()) {
    Utils::paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para acceder a esta pagina.');
}

$contenidoPrincipal=<<<EOS
    <h1>Proveedor de peliculas</h1>
EOS;


$contenidoPrincipal .= botonAnadirPelicula();
$contenidoPrincipal .= listaPeliculasDeProveedor(idUsuarioLogado());

require 'includes/vistas/comun/layout.php';