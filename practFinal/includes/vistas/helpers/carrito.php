<?php

use \es\ucm\fdi\aw\Aplicacion;
use \es\ucm\fdi\aw\carrito\Carrito;

function listaPeliculasCarrito($carrito) {
    $listaPelis = Carrito::devuelvePeliculasCarrito($carrito);
    if (count($listaPelis) == 0) {
        $html = '<p>Aun no hay peliculas añadidas</p>';
        return $html;
    }
    $html = listaFilaPeliculas($listaPelis);

    return $html;
}

function mostrarPrecio($carrito){
    $html = "<h2> Precio en total: {$carrito->precioTotal}</h2>";
    return $html;
}

function listaFilaPeliculas($peliculas) {
    $html = "<div class='row-pelis'><div class='scroll-pelis'>";
    foreach($peliculas as $pelicula) {
        $html .= portadaPelicula($pelicula);
    }
    $html .="</div></div>";
    return $html;
}


function portadaPelicula($pelicula)
{
    $app = Aplicacion::getInstance();
    $linkInfoPeli = $app->buildUrl('infoPelicula.php', [ 'id' => $pelicula->id ]);
    $rutaPortada = $app->buildUrl("almacen/portadas/{$pelicula->urlPortada}");
    $html = <<<EOS
    <div class="portada-peli">
    <a href="{$linkInfoPeli}"><img src="{$rutaPortada}" alt="{$pelicula->titulo}" /></a>
    </div>
    EOS;
    return $html;
}




