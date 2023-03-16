<?php

require_once '../includes/config.php';
require_once '../includes/vistas/helpers/usuarios.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/login.php';

$tituloPagina = 'Login';

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$password = $_POST["password"] ?? null;

$esValido = $name && $username && $password && !($usuario = Usuario::buscaUsuario($username));
if (!$esValido) {
	$htmlFormRegister = buildFormularioRegister();
	$contenidoPrincipal=<<<EOS
		<h1>Error</h1>
		<p>El nombre de usuario "{$username}" ya lo tiene otro usuario</p>
		$htmlFormRegister
	EOS;
	require '../includes/vistas/comun/layout.php';
	exit();
}

Usuario::crea($username, $password, $name, Usuario::USER_ROLE);

Utils::redirige(Utils::buildUrl('login.php'));

