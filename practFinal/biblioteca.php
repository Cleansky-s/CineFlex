<?php

use es\ucm\fdi\aw\compras\Compras;

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Portada';

$contenidoPrincipal=<<<EOS
<h1>Página de la biblioteca</h1>
EOS;

$peliculas = Compras::devuelvePeliculasCompradas($app->idUsuario());
$contenidoPrincipal .= listaFilaPeliculas($peliculas);

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);