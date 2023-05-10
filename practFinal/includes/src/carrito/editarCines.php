<?php


require_once __DIR__.'/../includes/config.php';

$tituloPagina = 'Editar cines';
$contenidoPrincipal='';
$htmlForm='';

if (! $app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::PROVEEDOR_ROLE)) {
    $app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para acceder a esta pagina.');
}

$idCine = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if (!$idCine) {
    $htmlForm = new \es\ucm\fdi\aw\cines\FormularioAddCine();
}
else {

    $cine = es\ucm\fdi\aw\cines\Cine::buscaPorId($idCine);
    if(!$cine){
        $app->paginaError(403, $tituloPagina, 'Error', 'No existe cine con esa id.');
    }
    if($app->idUsuario() != $cine->idProveedor){
        $app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No eres el propietario de este cine.');
    }


    $htmlForm = new \es\ucm\fdi\aw\cines\FormularioUpdateCines();


}

$htmlForm = $htmlForm->gestiona();

$contenidoPrincipal .= $htmlForm;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);