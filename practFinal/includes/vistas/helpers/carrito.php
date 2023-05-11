<?php

use \es\ucm\fdi\aw\Aplicacion;
use \es\ucm\fdi\aw\carrito\Carrito;
use es\ucm\fdi\aw\carrito\FormularioEliminaElemento;

function listaPeliculasCarrito() {
    $app = Aplicacion::getInstance();
    $idUsuario = $app->idUsuario();
    $listaPelis = Carrito::devuelvePeliculasCarrito($idUsuario);
    $precioTotal = Carrito::precioCarrito($idUsuario);
    if (count($listaPelis) == 0) {
        $html = '<p>Aun no hay peliculas añadidas</p>';
        return $html;
    }
    $html = listaCarrito($listaPelis);

    $htmlFormVaciar = new es\ucm\fdi\aw\carrito\FormularioVaciarCarrito();
    $htmlFormVaciar = $htmlFormVaciar->gestiona();

    $htmlFormCompra = new  es\ucm\fdi\aw\compras\FormularioCompraCarrito($precioTotal);
    $htmlFormCompra = $htmlFormCompra->gestiona();

    $html .= $htmlFormVaciar;
    $html .= $htmlFormCompra;

    return $html;
}

function mostrarPrecio($carrito){
    $html = "<h2> Precio en total: {$carrito->precioTotal}</h2>";
    return $html;
}

function listaCarrito($peliculas) {
    $html = "<div class='row-pelis'><div class='scroll-pelis'>";
    foreach($peliculas as $pelicula) {
        $html .= visualizaElemento($pelicula);
    }
    $html .="</div></div>";
    return $html;
}


function visualizaElemento($pelicula)
{
    $app = Aplicacion::getInstance();
    $linkInfoPeli = $app->buildUrl('infoPelicula.php', [ 'id' => $pelicula->id ]);
    $rutaPortada = $app->buildUrl("almacen/portadas/{$pelicula->urlPortada}");
    $htmlForm = new FormularioEliminaElemento($pelicula->id);
    $htmlForm = $htmlForm->gestiona();

    $html = <<<EOS
    <div class="elemento-compra">
        <div class="portada-elemento-carrito">
        <a href="{$linkInfoPeli}"><img src="{$rutaPortada}" alt="{$pelicula->titulo}" /></a>
        </div>
        <h4>Titulo:{$pelicula->titulo}</h4>
        <p>Precio: {$pelicula->precioCompra}</p>
        $htmlForm
    </div>
    EOS;
    return $html;
    
    return $html;
}




