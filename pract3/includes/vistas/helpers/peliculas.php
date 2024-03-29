<?php

use \es\ucm\fdi\aw\Aplicacion;
use \es\ucm\fdi\aw\peliculas\Pelicula;

function listaPeliculas() {
    $peliculas = Pelicula::devuelvePeliculas();
    if (count($peliculas) == 0) {
        $html = '<p>Aun no hay peliculas añadidas</p>';
        return $html;
    }
    $html = listaFilaPeliculas($peliculas);

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

function botonEditarPelicula($id) {
    $app = Aplicacion::getInstance();
    $editaURL = $app->buildUrl('peliculas/editarPelicula.php', [
        'id' => $id
    ]);
    return <<<EOS
    <a href="{$editaURL}">Editar Pelicula</a>
    EOS;
}

function listaPeliculasDeProveedor($idProveedor)
{
    $peliculas = Pelicula::buscaPorIdProveedor($idProveedor);
    $html = "<h3>Lista de peliculas pertenecientes al proveedor</h3>";

    if (count($peliculas) == 0) {
        $html .= '<p>Aun no hay peliculas de este proveedor</p>';
        return $html;
    }

    $html .= "<div>";
    foreach($peliculas as $pelicula) {
        $html .= portadaPelicula($pelicula);
        $html .= botonEditarPelicula($pelicula->id);
    }
    $html .="</div>";

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

function verPelicula($id) {
    $app = Aplicacion::getInstance();
    $pelicula = Pelicula::buscaPorId($id);
    $rutaPeli = $app->buildUrl("almacen/peliculas/{$pelicula->urlPelicula}");
    return <<<EOS
    <video width="1280" height="1024" controls autoplay>
        <source src="{$rutaPeli}">
        No se puede reproducir el trailer.
    </video>
    EOS;
}

function botonEnSuscripcion($id) {
    $app = Aplicacion::getInstance();
    $action = $app->buildUrl('/peliculas/verPelicula.php');
    $htmlButtonForm = <<<EOS
    <form action="{$action}" method="POST">
        <input type="hidden" name="id" value="{$id}" />
        <input type="submit" value="Ver Pelicula" />
    </form>
    EOS;
    return $htmlButtonForm;
}

function botonesCompra($pelicula) {
    $app = Aplicacion::getInstance();
    $action = $app->buildUrl('/compras/comprar.php', [ 'id' => $pelicula->id]);
    $htmlButtonForm = <<<EOS
    <div>
    <form id="compra" action="{$action}" method="GET">
        <label for="compra">Comprar</label>
        <input type="submit" for="compra" value="{$pelicula->precioCompra}" />
    </form>
    </div>
    EOS;
    $action = $app->buildUrl('/compras/alquilar.php');
    $htmlButtonForm .= <<<EOS
    <div>
    <form id="alquiler" action="{$action}" method="GET">
        <label for="alquiler">Alquiler</label>
        <input type="submit" for="alquiler" value="{$pelicula->precioAlquiler}" />
    </form>
    </div>
    EOS;
    return $htmlButtonForm;
}

function botonAlquiler($pelicula) {
    
}

function botonAnadirPelicula()
{
    $app = Aplicacion::getInstance();
    $urlAnadir = $app->buildUrl('/peliculas/editarPelicula.php');
    $htmlButtonForm = <<<EOS
    <form action="{$urlAnadir}" method="POST">
        <input type="submit" value="Añadir Pelicula" />
    </form>
    EOS;
    return $htmlButtonForm;
}