<?php

require_once __DIR__.'/includes/config.php';

$formRegistro = new \es\ucm\fdi\aw\usuarios\FormularioRegistro();
$formRegistro = $formRegistro->gestiona();

$linkLogin = $app->buildUrl('/login.php');

$tituloPagina = 'Registro';
$contenidoPrincipal=<<<EOF
  	<h1>Registro de usuario</h1>
    $formRegistro
    <p>Tienes cuenta? <a href="{$linkLogin}">Logeate</a></p>
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);