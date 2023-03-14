<?php
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';
require_once 'includes/vistas/helpers/mensajes.php';


$tituloPagina = 'Tablon';


$contenidoPrincipal = '<h1>Tablon de Anuncios</h1>';
$contenidoPrincipal .= listaMensajes();
if (estaLogado()) {
	$formNuevoMensaje = mensajeForm('mensajes/nuevoMensaje.php', 'Nuevo mensaje: ', 'Crear');
	$contenidoPrincipal .= <<<EOS
		<h1>Nuevo Tablon</h1>
		$formNuevoMensaje
	EOS;
}

require 'includes/vistas/comun/layout.php';
