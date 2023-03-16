<?php

function listaPeliculasDeProveedor($idProveedor)
{
    $peliculas = Pelicula::buscaPorIdProveedor($idProveedor);
    $html = "<h3>Lista de peliculas pertenecientes al proveedor</h3>";

    if (count($peliculas) == 0) {
        $html .= '<p>Aun no hay peliculas de este proveedor</p>';
        return $html;
    }

    $html .= "<div class='usuarios'>";
    foreach($peliculas as $pelicula) {
        $html .= portadaPelicula($pelicula->id);
        // $html .= botonEditarPeli($pelicula);
    }
    $html .="</div>";
    return $html;
}

function portadaPelicula($pelicula)
{
    $linkInfoPeli = Utils::buildUrl('infoPelicula.php' [
        $pelicula->id
    ]);
    $html = <<<EOS
    <a href="{$linkInfoPeli}">
        <img src="{$pelicula->urlPortada}" alt = "{$pelicula->titulo}"/>
    </a>
    EOS;

    return $html;
}

function detallesPelicula($idPelicula){
    $pelicula = Pelicula::buscaPorId($idPelicula);
    $urlCompra = Utils::buildUrl('/compra/compra.php');
    if($pelicula->getVisible()){
    $html = <<<EOS
    <h1>{$pelicula->getTitulo()}</h1>
    <div>
        <p> {$pelicula->getDescripcion()}</p>
    </div>
    "<form action="{$urlCompra}" method="POST">
        <input type="submit" value="Compra" />
    </form>";
    EOS;
    }
    else{
        $html = <<<EOS
        <h1> Pelicula no disponible</h1>
        EOS;
    }

    return $html;
}

function peliculaForm($action, $pelicula=null){
    $idProveedor = idUsuarioLogado();
    $htmlForm = <<<EOS
    <form action="{$action}" method="POST">
        <input type="hidden" name="idProveedor" value="{$idProveedor} "/>
        <fieldset>
            <label for="titulo">titulo:</label>
            <input type="text" name="titulo" value=""/>
            <label for="descripcion">descripcion:</label>
            <textarea name="descripcion"></textarea>
            <label for="generos">generos:</label>
            <select name="generos" multiple size = 14>
                <option value="1">action</option>
                <option value="2">adventure</option>
                <option value="3">animation</option>
                <option value="4">comedy</option>
                <option value="5">drama</option>
                <option value="6">fantasy</option>
                <option value="7">historical</option>
                <option value="8">horror</option>
                <option value="9">musical</option>
                <option value="10">noir</option>
                <option value="11">romance</option>
                <option value="12 fiction">science fiction</option>
                <option value="13">thriller</option>
                <option value="14">western</option>
            </select>
            <label for="urlPortada">urlPortada:</label>
            <input type="file" name="urlPortada" value=""/>
            <label for="urlTrailer">urlTrailer:</label>
            <input type="file" name="urlTrailer" value=""/>
            <label for="urlPelicula">urlPelicula:</label>
            <input type="file" name="urlPelicula" value=""/>
            <label for="precioCompra">precioCompra:</label>
            <input type="text" name="precioCompra" value="7.99"/>
            <label for="precioAlquiler">precioAlquiler:</label>
            <input type="text" name="precioAlquiler" value="2.99"/>
            <div>
            <input type="checkbox" name="enSuscripcion" value="enSuscripcion"/>
            <label for="enSuscripcion">enSuscripcion</label>
            </div>
            <div>
            <input type="checkbox" name="visible" value="visible"/>
            <label for="visible">visible</label>
            </div>
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