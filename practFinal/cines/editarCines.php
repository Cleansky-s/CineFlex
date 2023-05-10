<?php


require_once __DIR__.'/../includes/config.php';

$tituloPagina = 'Editar cines';
$contenidoPrincipal='';
$htmlForm='';

if (! $app->tieneRol(es\ucm\fdi\aw\usuarios\Usuario::PROVEEDOR_ROLE)) {
    $app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para acceder a esta pagina.');
}

$idCines = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$archivos = filter_input(INPUT_GET, 'archivos', FILTER_SANITIZE_SPECIAL_CHARS);

if (!$idCines) {
    $htmlForm = new \es\ucm\fdi\aw\peliculas\FormularioAddPelicula();
}
else {

    $pelicula = es\ucm\fdi\aw\peliculas\Pelicula::buscaPorId($idPelicula);
    if(!$pelicula){
        $app->paginaError(403, $tituloPagina, 'Error', 'No existe pelicula con esa id.');
    }
    if($app->idUsuario() != $pelicula->idProveedor){
        $app->paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No eres el propietario de esta pelicula.');
    }

    if (!$archivos) {
        $htmlForm = new \es\ucm\fdi\aw\peliculas\FormularioUpdatePelicula();
    }
    else {
        $htmlForm = new \es\ucm\fdi\aw\peliculas\FormularioArchivos();
    }
}

$htmlForm = $htmlForm->gestiona();

$contenidoPrincipal .= $htmlForm;


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);