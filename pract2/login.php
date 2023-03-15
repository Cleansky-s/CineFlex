<?php
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/login.php';


$tituloPagina = 'Login';

$htmlFormLogin = buildFormularioLogin();
$linkRegister = Utils::buildUrl('/register.php');

$contenidoPrincipal=<<<EOS
<h1>Acceso al sistema</h1>
$htmlFormLogin
<p>No tienes cuenta? <a href="{$linkRegister}">Registrate</a></p>
EOS;

require 'includes/vistas/comun/layout.php';
