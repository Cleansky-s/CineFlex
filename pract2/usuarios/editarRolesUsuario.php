<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/admin.php';

verificaLogado(Utils::buildUrl('/admin.php'));

$idUsuario = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$idUsuario) {
    Utils::redirige(Utils::buildUrl('/admin.php'));
}

$usuario = Usuario::buscaPorId($idUsuario);

$tituloPagina = 'Actualiza Roles Usuario';

if (!esAdmin() || $usuario->tieneRol($usuario::ADMIN_ROLE)) {
    Utils::paginaError(403, $tituloPagina, 'No tienes permisos para editar los roles del usuario');
}

$editaRolesUsuarioForm = rolesUsuarioForm($usuario);
$contenidoPrincipal = <<<EOS
	<h1>Editar Roles Usuario</h1>
	$editaRolesUsuarioForm
EOS;

require '../includes/vistas/comun/layout.php';
