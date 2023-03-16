<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';;
require_once '../includes/vistas/helpers/mensajes.php';

$idMensaje = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$idMensaje) {
    Utils::redirige(Utils::buildUrl('/tablon.php'));
}

$mensaje = Mensaje::buscaPorId($idMensaje);
if (!$mensaje) {
	Utils::redirige(Utils::buildUrl('/tablon.php'));
}

$numPagina = filter_input(INPUT_GET, 'numPagina', FILTER_SANITIZE_NUMBER_INT) ?? 1;
$numPorPagina = filter_input(INPUT_GET, 'numPorPagina', FILTER_SANITIZE_NUMBER_INT) ?? 3;

$tituloPagina = 'Mensaje';

$contenidoPrincipal = <<<EOS
<h1>Mensaje</h1>
<p>{$mensaje->mensaje}</p>
EOS;

/* Mensajes sin paginar 
$contenidoPrincipal .= listaMensajes($mensaje->id, true, $idMensaje);
*/

/* Mensajes paginados */
$contenidoPrincipal .= listaMensajesPaginados($mensaje->id, true, $idMensaje, $numPorPagina, $numPagina);

if (estaLogado()) {
	$formNuevoMensaje = mensajeForm('nuevoMensaje.php', 'Respuesta: ', 'Crear', $idMensaje);
	$contenidoPrincipal .= <<<EOS
		<h1>Responder</h1>
		$formNuevoMensaje
	EOS;
}

require '../includes/vistas/comun/layout.php';
