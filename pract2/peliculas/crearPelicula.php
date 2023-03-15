<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/peliculas.php';

verificaLogado(Utils::buildUrl('/admin.php'));

$idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$idUsuario) {
    Utils::redirige(Utils::buildUrl('/admin.php'));
}

$pelicula = Pelicula::buscaPorId($idPelicula);

$tituloPagina = 'Editar Pelicula';

if (!esProveedor() && !esAdmin()) {
    Utils::paginaError(403, $tituloPagina, 'No tienes permisos para editar la pelicula');
}

$crearPeliculaForm = peliculaForm('actualizarPelicula.php');
$contenidoPrincipal = <<<EOS
	<h1>Editar Roles Usuario</h1>
	$crearPeliculaForm
EOS;

require '../includes/vistas/comun/layout.php';