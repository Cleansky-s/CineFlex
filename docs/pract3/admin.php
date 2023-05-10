<?php

use es\ucm\fdi\aw\usuarios\Usuario;

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/admin.php';

$tituloPagina = 'Admin';
$contenidoPrincipal='';

if (!$app->tieneRol(Usuario::ADMIN_ROLE)) {
	$app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para administrar la web.');
}

$contenidoPrincipal=<<<EOS
	<h1>Lista de usuarios</h1>
EOS;

$contenidoPrincipal .= listaUsuarios();

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);