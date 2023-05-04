<?php

require_once __DIR__.'/../includes/config.php';

$tituloPagina = 'Compra de pelicula';

$contenidoPrincipal="<h1>En desarrollo</h1>";


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
