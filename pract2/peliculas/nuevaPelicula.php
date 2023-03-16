<?php

require_once 'includes/vistas/helpers/admin.php';
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/autorizacion.php';

$tituloPagina = 'Nueva Pelicula';

$idProveedor = filter_input(INPUT_POST, 'idProveedor', FILTER_SANITIZE_NUMBER_INT);
$titulo = filter_input(INPUT_POST, 'titulo', FILTER_SANITIZE_SPECIAL_CHARS);
$descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_SPECIAL_CHARS);
$generos = [];
$generos[] = filter_input(INPUT_POST, 'generos');
$urlPortada = filter_input(INPUT_POST, 'urlPortada', FILTER_SANITIZE_SPECIAL_CHARS);
$urlTrailer = filter_input(INPUT_POST, 'urlTrailer', FILTER_SANITIZE_SPECIAL_CHARS);
$urlPelicula = filter_input(INPUT_POST, 'urlPelicula', FILTER_SANITIZE_SPECIAL_CHARS);
$precioCompra = filter_input(INPUT_POST, 'precioCompra', FILTER_SANITIZE_NUMBER_FLOAT);
$precioAlquiler = filter_input(INPUT_POST, 'precioAlquiler', FILTER_SANITIZE_NUMBER_FLOAT);
$enSuscripcion = filter_input(INPUT_POST, 'enSuscripcion', FILTER_SANITIZE_SPECIAL_CHARS);
$fechaCreacion = filter_input(INPUT_POST, 'fechaCreacion', FILTER_SANITIZE_SPECIAL_CHARS);
$visible = filter_input(INPUT_POST, 'visible', FILTER_SANITIZE_SPECIAL_CHARS);

if (!esAdmin() && !esProveedor()) {
	Utils::paginaError(403, $tituloPagina, 'Acceso Denegado!', 'No tienes permisos suficientes para añadir una pelicula.');
}

if($idProveedor
	&& $titulo
	&& $descripcion
	&& $urlPortada
	&& $urlTrailer
	&& $urlPelicula
	&& $precioCompra
	&& $precioAlquiler
	&& $enSuscripcion
	&& $visible
	)
{
	if($generos[0] !== null){
		$pelicula = new Pelicula($titulo, $descripcion, $urlPortada, $urlTrailer, $urlPelicula, $enSuscripcion, $fechaCreacion, $visible, $precioCompra, $precioAlquiler, 0.0, 0, $idProveedor, $generos);
		$pelicula->guarda();
	}
}


require 'includes/vistas/comun/layout.php';