<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/mensajes.php';

verificaLogado(Utils::buildUrl('/tablon.php'));

$idMensaje = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$idMensaje) {
    Utils::redirige(Utils::buildUrl('/tablon.php'));
}

$mensaje = Mensaje::buscaPorId($idMensaje);
// if (!$idMensaje) {
//     Utils::redirige(Utils::buildUrl('/tablon.php'));
// }

$tituloPagina = 'Actualiza Mensaje';

if (idUsuarioLogado() != $mensaje->idAutor && ! esAdmin()) {
    Utils::paginaError(403, $tituloPagina, 'No tienes permisos para editar este mensaje');
}

$idMensajeRetorno = filter_input(INPUT_GET, 'idMensajeRetorno', FILTER_SANITIZE_NUMBER_INT);

$editaMensajeForm = mensajeForm('actualizaMensaje.php', 'Mensaje: ', 'Actualiza', $idMensajeRetorno, $idMensaje, $mensaje->mensaje);
$contenidoPrincipal = <<<EOS
	<h1>Editar Mensaje</h1>
	<p>Mensaje: {$mensaje->mensaje}</p>
	$editaMensajeForm
EOS;

require '../includes/vistas/comun/layout.php';
