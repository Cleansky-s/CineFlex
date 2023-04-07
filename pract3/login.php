<?php


require_once __DIR__.'/includes/config.php';

$formLogin = new \es\ucm\fdi\aw\usuarios\FormularioLogin();
$formLogin = $formLogin->gestiona();

$linkRegister = $app->buildUrl('/register.php');

$tituloPagina = 'Login';
$contenidoPrincipal=<<<EOF
  	<h1>Acceso al sistema</h1>
    $formLogin
    <p>No tienes cuenta? <a href="{$linkRegister}">Registrate</a></p>
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);