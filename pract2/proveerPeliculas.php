<?php 
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';

$tituloPagina = 'Proveer Peliculas';

if (! esProveedor()) {
    Utils::paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para acceder a esta pagina.');
}

$contenidoPrincipal=<<<EOS
    <h1>Proveedor de cines</h1>
    <p>Aqui estarán las opciones para añadir, modificar o borrar cines</p>
EOS;


$contenidoPrincipal .= "<h3>Lista de peliculas pertenecientes al proveedor</h3>"
$contenidoPrincipal .= listaPeliculasDeProveedor(idUsuarioLogado());

require 'includes/vistas/comun/layout.php';