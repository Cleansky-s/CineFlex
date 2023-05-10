<?php
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\carrito\Carrito;

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/carrito.php';

$tituloPagina = 'Carrito';

$idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$app = Aplicacion::getInstance();
$carrito = Carrito::buscaPorIdCarrito($app->idUsuario());
$peliculas = [];
if(!$carrito){
	$carrito = Carrito::crea($app->idUsuario(), $peliculas, 0);
}

if($idPelicula){
	$peliculasCarrito[] = $idPelicula;
	$carrito->idPeliculas=$peliculasCarrito;
	$carrito->guarda();
}

$contenidoPrincipal=<<<EOS
<h1>Página del carrito</h1>
EOS;

$contenidoPrincipal .= listaPeliculasCarrito($carrito);
$contenidoPrincipal .= mostrarPrecio($carrito);


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);