<?php

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Portada';

$contenidoPrincipal="<h1>Lista de todas las peliculas</h1>";

$contenidoPrincipal .= listaPeliculas();


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);