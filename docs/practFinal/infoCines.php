<?php

use es\ucm\fdi\aw\cines\Cine;


require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/cines.php';
$tituloPagina = 'Cine';

$idCines = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

$Cines = Cine::buscaPorId($idCines);
if(!$Cines){
    $app->paginaError(403, 'El cine no existe');
}


$contenidoPrincipal = "<div class='centerer'>";
$contenidoPrincipal .= detallesCines($Cines);
$contenidoPrincipal .= "</div>";


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);
