<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/cines.php';

$tituloPagina = 'Portada';

$contenidoPrincipal="<h1>Lista de todas las cines</h1>";

$contenidoPrincipal .= listCines();
$contenidoPrincipal .= createMap();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);

