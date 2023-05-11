<?php
use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\carrito\Carrito;

require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/includes/vistas/helpers/carrito.php';

$tituloPagina = 'Carrito';

$app = Aplicacion::getInstance();

$contenidoPrincipal=<<<EOS
<h1>Página del carrito</h1>
EOS;

// dentro de la funcion esta la llamada al formulario de compra de carrito.
$contenidoPrincipal .= listaPeliculasCarrito();


$params = ['tituloPagina' => $tituloPagina, 'contenidoPrincipal' => $contenidoPrincipal];
$app->generaVista('/plantillas/plantilla.php', $params);