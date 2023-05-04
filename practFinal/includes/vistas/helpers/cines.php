<?php

use \es\ucm\fdi\aw\Aplicacion;
use \es\ucm\fdi\aw\cines\Cine;

function listCines() {
    $cines = Cine::devuelveCine();
    if (count($cines) == 0) {
        $html = '<p>Aun no hay Cine añadidas</p>';
        return $html;
    }
    $html = listaFilaCine($cines);

    return $html;
}

function listaFilaCine($cines) {
    $html = "<div class='row-cines'><div class='scroll-cines'>";
    foreach($cines as $cine) {
        $html .= portadaCine($cine);
    }
    $html .="</div></div>";
    return $html;
}

function botonEditarCine($id) {
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
    $cines = Cine::buscaPorIdProveedor($idProveedor);
    $html = "<h3>Lista de cines pertenecientes al proveedor</h3>";

    if (count($cines) == 0) {
        $html .= '<p>Aun no hay cines de este proveedor</p>';
        return $html;
    }

    $html .= "<div>";
    foreach($cines as $cine) {
        $html .= portadaCine($cine);
        $html .= botonEditarCine($cine->id);
    }
    $html .="</div>";

    return $html;
}

function portadaCine($cines)
{
    $app = Aplicacion::getInstance();
    $html = <<<EOS
    <div class="portada-cine">
    </div>
    EOS;
    return $html;
}

function detallesPelicula($pelicula){
    // a hacer
    $app = Aplicacion::getInstance();
    $rutaPortada = $app->buildUrl("almacen/portadas/{$pelicula->urlPortada}");
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
        

    $html .= "</div>";
    return $html;
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