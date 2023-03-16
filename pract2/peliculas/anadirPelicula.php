<?php
require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/peliculas.php';

verificaLogado(Utils::buildUrl('/proveerPeliculas.php'));

$pelicula = Pelicula::buscaPorId($idPelicula);

$tituloPagina = 'Editar Pelicula';

if (!esProveedor() && !esAdmin()) {
    Utils::paginaError(403, $tituloPagina, 'No tienes permisos para editar la pelicula');
}

$crearPeliculaForm = peliculaForm('nuevaPelicula.php');
$contenidoPrincipal = <<<EOS
	<h1>Añadir Pelicula</h1>
	$crearPeliculaForm
EOS;

require '../includes/vistas/comun/layout.php';