<?php

use es\ucm\fdi\aw\usuarios\Usuario;

require_once __DIR__.'includes/config.php';
require_once __DIR__.'includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Proveer Peliculas';

if (! $app->tieneRol(Usuario::PROVEEDOR_ROLE)) {
    $app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para acceder a esta pagina.');
}

$contenidoPrincipal=<<<EOS
    <h1>Proveedor de peliculas</h1>
EOS;


$contenidoPrincipal .= botonAnadirPelicula();
$contenidoPrincipal .= listaPeliculasDeProveedor($app->idUsuarioLogueado());

require 'includes/vistas/comun/layout.php';