<?php

use \es\ucm\fdi\aw\Aplicacion;
use \es\ucm\fdi\aw\comentarios\Carrito;

function listaPeliculas($idUsuario) {
    $carro = Carrito::devuelvePeliculas($idUsuario);
    if (count($carro) == 0) {
        $html = '<p>Aun no hay peliculas añadidas</p>';
        return $html;
    }
    $html = listaFilaPeliculas($carro);

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

function detallesPelicula($pelicula){
    // a hacer
    $app = Aplicacion::getInstance();
    $rutaPortada = $app->buildUrl("almacen/portadas/{$pelicula->urlPortada}");
    $rutaTrailer = $app->buildUrl("almacen/trailers/{$pelicula->urlTrailer}");
    $html = <<<EOS
    <div class="info-peli">
    <h1>{$pelicula->getTitulo()}</h1>
    <img class='portada-peli' src="{$rutaPortada}" alt="{$pelicula->titulo}" />
    <p>Valoracion media: {$pelicula->valoracionMedia} con un total de {$pelicula->valoracionCuenta} valoraciones</p>
    <p>{$pelicula->descripcion}</p>
    <div class="info-generos">
    <p>Generos: {$pelicula->generosToString()}</p>
    <p>Fecha de salida: {$pelicula->fechaCreacion}</p>
    </div>
    EOS;

    if($pelicula->visible){
        // Si una pelicula esta en suscripcion mostramos solo el boton de "Ver"
        // Si no, mostramos el boton de comprar y alquilar
        if($pelicula->enSuscripcion){
            $html .= botonEnSuscripcion($pelicula->id);
        }
        else {
            $html .= botonesCompra($pelicula);
        }
    }
    else {
        $html .= "<h4>Pelicula no disponible para ver</h4>";
    }
    
    $html .= <<<EOS
    <h3>Trailer</h3>
    <video width="320" height="240" controls autoplay muted>
        <source src="{$rutaTrailer}">
        No se puede reproducir el trailer.
    </video>
    EOS;

    $html .= "</div>";
    return $html;
}



