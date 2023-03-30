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
    <p>{$pelicula->descripcion}</p>
    <p>Generos: {$pelicula->generosToString()}</p>
    <p>Fecha de salida: {$pelicula->fechaCreacion}</p>
    <p>Valoracion media: {$pelicula->valoracionMedia} con un total de {$pelicula->valoracionCuenta} valoraciones</p>
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

function peliculaForm($action, $pelicula){
    $app = Aplicacion::getInstance();
    $idProveedor = $app->idUsuarioLogueado();
    $htmlForm = <<<EOS
    <form action="{$action}" enctype='multipart/form-data' method="POST">
        <input type="hidden" name="idProveedor" value="{$idProveedor} "/>
        <input type="hidden" name="valoracionMedia" value="{$pelicula->valoracionMedia}" />
        <input type="hidden" name="valoracionCuenta" value="{$pelicula->valoracionCuenta}" />
        <fieldset>
            <label for="titulo">titulo:</label>
            <input type="text" name="titulo" required value="{$pelicula->titulo}"/>

            <label for="descripcion">descripcion:</label>
            <textarea name="descripcion" rows=10 columns=100 required >{$pelicula->descripcion}</textarea>

            <label for="generos">generos:</label>
            <select name="generos[]" multiple required size = 14>
    EOS;
    $generos = Pelicula::GENEROS;
    foreach($generos as $idGenero => $nombreGenero){
        $selected = ($pelicula->tieneGenero($idGenero)) ? 'selected' : '';
        $htmlForm .= "<option value='{$idGenero}' $selected>{$nombreGenero}</option>";
    }

    // no es posible poner el input file en predeterminado a como lo tenia antes la pelicula...


    $enSuscripcionChecked = ($pelicula->enSuscripcion) ? 'checked' : "";
    $visibleChecked = ($pelicula->visible) ? 'checked' : ""; 

    $htmlForm .= <<<EOS
            </select>
            <label for="urlPortada">urlPortada:</label>
            <input type="file" name="urlPortada" accept="image/*" required/>

            <label for="urlTrailer">urlTrailer:</label>
            <input type="file" name="urlTrailer" accept="video/*" required/>

            <label for="urlPelicula">urlPelicula:</label>
            <input type="file" name="urlPelicula" accept="video/*" required/>

            <label for="precioCompra">precioCompra:</label>
            <input type="number" step="0.01" max=99.99 min=0 name="precioCompra" value="{$pelicula->precioCompra}" required/>

            <label for="precioAlquiler">precioAlquiler:</label>
            <input type="number" step="0.01" max=99.99 min=0 name="precioAlquiler" value="{$pelicula->precioAlquiler}" required/>

            <div>
            <input type="checkbox" name="enSuscripcion" value="enSuscripcion" $enSuscripcionChecked/>
            <label for="enSuscripcion">enSuscripcion</label>
            </div>

            <div>
            <input type="checkbox" name="visible" value="visible" $visibleChecked/>
            <label for="visible">visible</label>
            </div>

            <label for="fechaCreacion" >Fecha de Salida:</label>
            <input type="date" name="fechaCreacion" value="{$pelicula->fechaCreacion}"/>

            <input type="submit" value="Añadir" />
        </fieldset>
    </form>
    EOS;

    return $htmlForm;
}

function botonAnadirPelicula()
{
    $app = Aplicacion::getInstance();
    $urlAnadir = $app->buildUrl('/peliculas/anadirPelicula.php');
    $htmlButtonForm = <<<EOS
    <form action="{$urlAnadir}" method="POST">
        <input type="submit" value="Añadir Pelicula" />
    </form>
    EOS;
    return $htmlButtonForm;
}