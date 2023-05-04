<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';

$contenidoPrincipal=<<<EOS
<h1>Página del carrito</h1>
	<p> Aquí estara el contenido del carrito. </p>
EOS;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);