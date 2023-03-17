<?php


function listaPeliculas() {
    $peliculas = Pelicula::devuelvePeliculas();
    $html='';
    if (count($peliculas) == 0) {
        $html .= '<p>Aun no hay peliculas de este proveedor</p>';
        return $html;
    }
    $html .= creaPortadas($peliculas);

    return $html;
}

function creaPortadas($peliculas) {
    $html = "<div class='portada-pelis'>";
    foreach($peliculas as $pelicula) {
        $html .= portadaPelicula($pelicula);
    }
    $html .="</div>";
    return $html;
}

function listaPeliculasDeProveedor($idProveedor)
{
    $peliculas = Pelicula::buscaPorIdProveedor($idProveedor);
    $html = "<h3>Lista de peliculas pertenecientes al proveedor</h3>";

    if (count($peliculas) == 0) {
        $html .= '<p>Aun no hay peliculas de este proveedor</p>';
        return $html;
    }

    $html .= creaPortadas($peliculas);

    return $html;
}

function portadaPelicula($pelicula)
{
    $linkInfoPeli = Utils::buildUrl('infoPelicula.php', [ 'id' => $pelicula->id ]);
    $rutaPortada = Utils::buildUrl("almacen/portadas/{$pelicula->urlPortada}");
    $html = <<<EOS
    <a href="{$linkInfoPeli}">
        <img src="{$rutaPortada}" alt = "{$pelicula->titulo}" type=image/webp />
    </a>
    EOS;
    return $html;
}

function peliculaForm($action, $pelicula=null){
    $idProveedor = idUsuarioLogado();
    $htmlForm = <<<EOS
    <form action="{$action}" enctype='multipart/form-data' method="POST">
        <input type="hidden" name="idProveedor" value="{$idProveedor} "/>
        <fieldset>
            <label for="titulo">titulo:</label>
            <input type="text" name="titulo" required/>
            <label for="descripcion">descripcion:</label>
            <textarea name="descripcion" rows=10 columns=100 required></textarea>
            <label for="generos">generos:</label>
            <select name="generos[]" multiple required size = 14>
    EOS;
    $generos = Pelicula::GENEROS;
    foreach($generos as $idGenero => $nombreGenero){
        $htmlForm .= "<option value='{$idGenero}' >{$nombreGenero}</option>";
    }
    $htmlForm .= <<<EOS
            </select>
            <label for="urlPortada">urlPortada:</label>
            <input type="file" name="urlPortada" accept="image/*" required/>
            <label for="urlTrailer">urlTrailer:</label>
            <input type="file" name="urlTrailer" accept="video/*" required/>
            <label for="urlPelicula">urlPelicula:</label>
            <input type="file" name="urlPelicula" accept="video/*" required/>
            <label for="precioCompra">precioCompra:</label>
            <input type="number" step="0.01" max=99.99 min=0 name="precioCompra" value="7.99" required/>
            <label for="precioAlquiler">precioAlquiler:</label>
            <input type="number" step="0.01" max=99.99 min=0 name="precioAlquiler" value="2.99" required/>
            <div>
            <input type="checkbox" name="enSuscripcion" value="enSuscripcion" />
            <label for="enSuscripcion">enSuscripcion</label>
            </div>
            <div>
            <input type="checkbox" name="visible" value="visible" />
            <label for="visible">visible</label>
            </div>
            <label for="fechaCreacion">Fecha de Salida:</label>
            <input type="date" name="fechaCreacion" />

            <input type="submit" value="Añadir" />
        </fieldset>
    </form>
    EOS;
    return $htmlForm;
}

function botonAnadirPelicula()
{
    $urlAnadir = Utils::buildUrl('/peliculas/anadirPelicula.php');
    $htmlButtonForm = <<<EOS
    <form action="{$urlAnadir}" method="POST">
        <input type="submit" value="Añadir Pelicula" />
    </form>
    EOS;
    return $htmlButtonForm;
}