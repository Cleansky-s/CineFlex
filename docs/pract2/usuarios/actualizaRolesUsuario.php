<?php

require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';

verificaLogado(Utils::buildUrl('/admin.php'));

$idUsuario = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

$rolProveedor = filter_input(INPUT_POST, 'proveedor', FILTER_SANITIZE_NUMBER_INT);

$rolModerador = filter_input(INPUT_POST, 'moderador', FILTER_SANITIZE_NUMBER_INT);

$usuario = Usuario::buscaPorId($idUsuario);

if(!esAdmin() || $usuario->tieneRol($usuario::ADMIN_ROLE)){
    Utils::paginaError(403, $tituloPagina, 'No tienes permisos para editar los roles del usuario');
}

$roles = [];

$roles[] = $usuario::USER_ROLE;

if($rolProveedor) {
    $roles[] = $rolProveedor;
}

if($rolModerador) {
    $roles[] = $rolModerador;
}

$usuario->setRoles($roles);

$usuario->guarda();

Utils::redirige(Utils::buildUrl('/admin.php'));