<?php

require_once 'includes/vistas/helpers/admin.php';
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';

$tituloPagina = 'Admin';

if (!esAdmin()) {
	Utils::paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para administrar la web.');
}

$contenidoPrincipal=<<<EOS
	<h1>Lista de usuarios</h1>
EOS;

$contenidoPrincipal .= listaUsuarios();


require 'includes/vistas/comun/layout.php';
?>
