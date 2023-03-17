<?php


require_once '../includes/config.php';
require_once '../includes/vistas/helpers/autorizacion.php';
require_once '../includes/vistas/helpers/almacen.php';

$tituloPagina = 'Nueva Pelicula';

if (!esProveedor() && !esAdmin()) {
    Utils::paginaError(403, $tituloPagina, 'No tienes permisos para añadir una pelicula');
}

// INPUTS POST
$idProveedor = filter_input(INPUT_POST, 'idProveedor', FILTER_SANITIZE_NUMBER_INT);
$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
$descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
$generos = $_POST['generos'];
$precioCompra = $_POST['precioCompra'];
$precioAlquiler = $_POST['precioAlquiler'];
$fechaCreacion = filter_input(INPUT_POST, 'fechaCreacion', FILTER_SANITIZE_SPECIAL_CHARS);

$enSuscripcion = (isset($_POST['enSuscripcion'])) ? 1 : 0;
$visible = (isset($_POST['visible'])) ? 1 : 0;

if(!($idProveedor
	&& $titulo
	&& $descripcion
	&& $precioCompra
	&& $precioAlquiler
	&& $fechaCreacion
	&& count($generos)>0
	))
{
	// error (devolver a la pagina del formulario)
}

$pelicula = Pelicula::crea($titulo, $descripcion, "tmp", "tmp", "tmp", $enSuscripcion, $fechaCreacion, $visible, $precioCompra, $precioAlquiler, 0, 0, $idProveedor, $generos);

	// basename() puede evitar ataques de denegación de sistema de ficheros;
	// podría ser apropiada más validación/saneamiento del nombre del fichero, pero solo lo usamos para coger la extension (por ahora)
	// En la practica 3 hacer full saneamiento de los archivos

if ($_FILES['urlPortada']['error'] == UPLOAD_ERR_OK) {

	$nombre = basename($_FILES['urlPortada']['name']);
	$extension = pathinfo($nombre, PATHINFO_EXTENSION);
	if(!(array_search($extension, Pelicula::EXTENSIONES_PERMITIDAS_IMG) !== false)){
		// Error
	}
	$tmp_name = $_FILES['urlPortada']['tmp_name'];
	$fichero = "{$pelicula->id}.{$extension}";
	$rutaPortada = implode(DIRECTORY_SEPARATOR, [RUTA_ALMACEN_PORTADAS, $fichero]);
	if(!move_uploaded_file($tmp_name, $rutaPortada)){
		// error
	}
	$pelicula->setUrlPortada($fichero);
}
if ($_FILES['urlTrailer']['error'] == UPLOAD_ERR_OK) {

	$nombre = basename($_FILES['urlTrailer']['name']);
	$extension = pathinfo($nombre, PATHINFO_EXTENSION);
	if(!(array_search($extension, Pelicula::EXTENSIONES_PERMITIDAS_VIDEO) !== false)){
		// Error
	}
	
	$tmp_name = $_FILES['urlTrailer']['tmp_name'];
	$fichero = "{$pelicula->id}.{$extension}";
	$rutaTrailer = implode(DIRECTORY_SEPARATOR, [RUTA_ALMACEN_TRAILERS, $fichero]);
	if(!move_uploaded_file($tmp_name, $rutaTrailer)){
		// error
	}
	$pelicula->setUrlTrailer($fichero);
}
if ($_FILES['urlPelicula']['error'] == UPLOAD_ERR_OK) {

	$nombre = basename($_FILES['urlPelicula']['name']);
	$extension = pathinfo($nombre, PATHINFO_EXTENSION);
	if(!(array_search($extension, Pelicula::EXTENSIONES_PERMITIDAS_VIDEO) !== false)){
		// Error
	}
	$tmp_name = $_FILES['urlPelicula']['tmp_name'];
	$fichero = "{$pelicula->id}.{$extension}";
	$rutaPelicula = implode(DIRECTORY_SEPARATOR, [RUTA_ALMACEN_PELICULAS, $fichero]);
	if(!move_uploaded_file($tmp_name, $rutaPelicula)){
		// Error
	}
	$pelicula->setUrlPelicula($fichero);
}

$pelicula->guarda();

Utils::redirige(Utils::buildUrl('/proveerPeliculas.php'));


require '../includes/vistas/comun/layout.php';