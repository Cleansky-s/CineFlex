<?php

use es\ucm\fdi\aw\usuarios\FormularioRoles;
use es\ucm\fdi\aw\usuarios\Usuario;

require_once __DIR__ .'/../includes/config.php';
require_once __DIR__ .'/../includes/vistas/helpers/admin.php';


$idUsuario = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);


if (!$app->tieneRol(Usuario::ADMIN_ROLE)) {
    $app->paginaError(403, $tituloPagina, 'No tienes permisos para editar los roles del usuario');
}

$usuario = Usuario::buscaPorId($idUsuario);

if(!$usuario) {
    $app->paginaError(403, $tituloPagina, 'No existe el usuario');
}

$tituloPagina = 'Actualiza Roles Usuario';

$editaRolesUsuarioForm = new FormularioRoles();
$editaRolesUsuarioForm = $editaRolesUsuarioForm->gestiona();

$contenidoPrincipal = <<<EOS
	<h1>Editar Roles Usuario</h1>
	$editaRolesUsuarioForm
EOS;

$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
