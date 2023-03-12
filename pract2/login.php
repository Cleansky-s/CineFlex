<?php
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/login.php';


$tituloPagina = 'Login';

$htmlFormLogin = buildFormularioLogin();
$contenidoPrincipal=<<<EOS
<h1>Acceso al sistema</h1>
$htmlFormLogin
EOS;

require 'includes/vistas/comun/layout.php';
