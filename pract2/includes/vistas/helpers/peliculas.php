<?php

function listaPeliculasDeProveedor($idProveedor)
{
    $peliculas = Pelicula::buscaPorIdProveedor($idProveedor);

    if (count($peliculas) == 0) {
        return '';
    }

    $html = "<div class='usuarios'>";
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

function peliculaForm($action, $pelicula=null){

    $htmlForm = <<<EOS
    <form action="{$action}" method="POST">
        <fieldset>

            <label for="descripcion">Username:</label> <textarea id="descripcion" name="descripcion"></textarea>
        </fieldset>
    </form>
    EOS;
}
