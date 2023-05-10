<?php

require_once __DIR__.'/includes/config.php';

$formRegistro = new \es\ucm\fdi\aw\usuarios\FormularioRegistro();
$formRegistro = $formRegistro->gestiona();

$linkLogin = $app->buildUrl('/login.php');

$tituloPagina = 'Registro';
$contenidoPrincipal=<<<EOF
<div class="centerer">
    $formRegistro
    <p>Tienes cuenta? <a href="{$linkLogin}">Logeate</a></p>
</div>
EOF;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);