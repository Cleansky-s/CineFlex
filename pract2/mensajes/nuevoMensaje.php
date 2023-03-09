<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';

verificaLogado(Utils::buildUrl('/tablon.php'));

$textoMensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_SPECIAL_CHARS);
$idMensajeRetorno = filter_input(INPUT_POST, 'idMensajeRetorno', FILTER_SANITIZE_NUMBER_INT);

if ($textoMensaje) {
    $mensaje = Mensaje::crea(idUsuarioLogado(), $textoMensaje, $idMensajeRetorno);
    $mensaje->guarda();
}

if ($idMensajeRetorno) {
    Utils::redirige(Utils::buildUrl('/mensajes/mensajes.php', ['id' => $idMensajeRetorno]));
} else {
    Utils::redirige(Utils::buildUrl('/tablon.php'));
}