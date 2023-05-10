<?php

use es\ucm\fdi\aw\usuarios\Usuario;

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/cines.php';

$tituloPagina = 'Proveer Cines';

if (! $app->tieneRol(Usuario::PROVEEDOR_ROLE)) {
    $app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para acceder a esta pagina.');
}

$contenidoPrincipal=<<<EOS
    <h1>Proveedor de cines</h1>
    <p>Aqui estarán las opciones para añadir, modificar o borrar cines</p>
EOS;

$contenidoPrincipal .= botonAnadirCines();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);

