<?php

require_once __DIR__.'/includes/config.php';

$tituloPagina = 'Portada';
$contenidoPrincipal=<<<EOS
  <h1>Página principal</h1>
  <p> Aquí está el contenido público, visible para todos los usuarios. </p>
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);