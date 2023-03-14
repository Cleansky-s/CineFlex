<?php
	session_start();
	require_once __DIR__.'/../helpers/usuarios.php';
?>
<?php

	require_once 'includes/config.php';
	require_once 'includes/vistas/helpers/autorizacion.php';

	$tituloPagina = 'Admin';

	if (! esAdmin()) {
		Utils::paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para administrar la web.');
	}

	$contenidoPrincipal=<<<EOS
		<a href="administrar.php"> Administrar </a>
		<h1>Consola de administración</h1>
		<p>Aquí estarían todos los controles de administración</p>
	EOS;

	require 'includes/vistas/comun/layout.php';
?>
