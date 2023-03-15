<?php

require_once '../includes/config.php';
require_once '../includes/vistas/helpers/usuarios.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/login.php';

$tituloPagina = 'Login';

$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
$password = $_POST["password"] ?? null;

$esValido = $username && $password && ($usuario = Usuario::login($username, $password));
if (!$esValido) {
	$htmlFormLogin = buildFormularioLogin($username, $password);
	$contenidoPrincipal=<<<EOS
		<h1>Error</h1>
		<p>El usuario o contraseña no son válidos.</p>
		$htmlFormLogin
	EOS;
	require 'includes/vistas/comun/layout.php';
	exit();
}

$_SESSION['idUsuario'] = $usuario->id;
$_SESSION['roles'] = $usuario->roles;
$_SESSION['nombre'] = $usuario->nombre;

$contenidoPrincipal=<<<EOS
	<h1>Bienvenido ${_SESSION['nombre']}</h1>
	<p>Usa el menú de la izquierda para navegar.</p>
EOS;

require '../includes/vistas/comun/layout.php';
