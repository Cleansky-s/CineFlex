<?php
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/login.php';


$tituloPagina = 'Login';

$htmlFormLogin = buildFormularioRegister();
$linkLogin = Utils::buildUrl("/login.php");

$contenidoPrincipal=<<<EOS
<h1>Acceso al sistema</h1>
$htmlFormLogin
<p>Tienes cuenta? <a href="{$linkLogin}">Logeate</a></p>
EOS;

require 'includes/vistas/comun/layout.php';