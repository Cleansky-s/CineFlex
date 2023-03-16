<?php
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/usuarios.php';

logout();

$tituloPagina = 'Logout';

$contenidoPrincipal=<<<EOS
	<h1>Hasta pronto!</h1>
EOS;

require 'includes/vistas/comun/layout.php';
