<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';

verificaLogado(Utils::buildUrl('/tablon.php'));

$idMensaje = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$idMensaje) {
    Utils::redirige(Utils::buildUrl('/tablon.php'));
}

$mensaje = Mensaje::buscaPorId($idMensaje);
if (idUsuarioLogado() != $mensaje->idAutor && ! esAdmin()) {
    Utils::paginaError(403, 'Borrar Mensaje', 'No tienes permisos para borrar este mensaje');
}

Mensaje::borraPorId($idMensaje);

$idMensajeRetorno = filter_input(INPUT_POST, 'idMensajeRetorno', FILTER_SANITIZE_NUMBER_INT);
if ($idMensajeRetorno != null) {
    Utils::redirige(Utils::buildUrl('/mensajes/mensajes.php', ['id' => $idMensajeRetorno]));
} else {
    Utils::redirige(Utils::buildUrl('/tablon.php'));
}
