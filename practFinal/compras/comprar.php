<?php

require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/vistas/helpers/compra.php';

$idPelicula = filter_input(INPUT_POST, 'idPelicula', FILTER_SANITIZE_NUMBER_INT);
$precio = $_POST['precio'];

$tituloPagina = 'Compra de pelicula';

$contenidoPrincipal="<h1>En desarrollo</h1>";
$contenidoPrincipal .= detallesCompra($idPelicula, $precio);

$htmlForm = new es\ucm\fdi\aw\compras\FormularioAddCompra();
$htmlForm = $htmlForm->gestiona();
$contenidoPrincipal .= $htmlForm;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
