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
function geocodeAddress($address) {
    $accessToken = 'pk.eyJ1IjoiY2xlYW5za3kxIiwiYSI6ImNsaGdtZ3I0MjAyM2kzZXJ6NXFpdWo4a3cifQ.8yFg-lyDUMVs9KqajQ5qDA';

    $url = 'https://api.mapbox.com/geocoding/v5/mapbox.places/' . urlencode($address) . '.json?access_token=' . $accessToken;
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    $coordinates = $data['features'][0]['center'];
    return $coordinates;
}

function createMap() {
    $markers = Cine::devuelveCine();
    $accessToken = 'pk.eyJ1IjoiY2xlYW5za3kxIiwiYSI6ImNsaGdtZ3I0MjAyM2kzZXJ6NXFpdWo4a3cifQ.8yFg-lyDUMVs9KqajQ5qDA';
    $mapHtml = file_get_contents(__DIR__.'/map.php');
    foreach($markers as $marker) {
        $coordinates = geocodeAddress($marker->getDireccion(), $accessToken);
        $markerHtml = '<script>const marker' . $marker->getId() . ' = new mapboxgl.Marker()';
        $markerHtml .= '.setLngLat([' . $coordinates[0] . ', ' . $coordinates[1] . '])';
        $markerHtml .= '.setPopup(new mapboxgl.Popup().setHTML("' . $marker->getNombre() . '"))';
        $markerHtml .= '.setPopup(new mapboxgl.Popup({className: "marker-popup"}).setHTML("' . $marker->getNombre() . '"))';
        $markerHtml .= '.addTo(map);</script>';
        $mapHtml .= $markerHtml;
    }
    return $mapHtml;
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
    $editaURL = $app->buildUrl('cines/editarCines.php', [
        'id' => $id
    ]);
    return <<<EOS
    <a href="{$editaURL}">Editar Cine</a>
    EOS;
}

function listaCinesDeProveedor($idProveedor)
{
    $cines = Cine::devuelveCine($idProveedor);
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


/*
function portadaPelicula($pelicula)
{
    $linkInfoPeli = Utils::buildUrl('infoPelicula.php', [ 'id' => $pelicula->id ]);
    $rutaPortada = Utils::buildUrl("almacen/portadas/{$pelicula->urlPortada}");
    $html = <<<EOS
    <div class="portada-peli">
    <a href="{$linkInfoPeli}"><img src="{$rutaPortada}" alt="{$pelicula->titulo}" /></a>
    </div>
    EOS;
    return $html;
}
*/
function portadaCine($cines)
{
    $app = Aplicacion::getInstance();
    $linkInfoCine =  $app->buildUrl('infoCines.php', [ 'id' => $cines->id ]);
    $html = <<<EOS
    <div class="portada-cine">
     <a href="{$linkInfoCine}">$cines->nombre</a>
    </div>
    EOS;
    return $html;
}

function detallesCines($cines){
    $app = Aplicacion::getInstance();
    $html = <<<EOS
    <div class="info-cines">
    <h1>{$cines->getNombre()}</h1>
    <p>Direccion: {$cines->getDireccion()}</p>
    </div>
    EOS;
    return $html;
}

function botonAnadirCines()
{
    $app = Aplicacion::getInstance();
    $urlAnadir = $app->buildUrl('/cines/editarCines.php');
    $htmlButtonForm = <<<EOS
    <form action="{$urlAnadir}" method="POST">
        <input type="submit" value="Añadir Cines" />
    </form>
    EOS;
    return $htmlButtonForm;
}