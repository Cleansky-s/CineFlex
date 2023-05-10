<?php

    use es\ucm\fdi\aw\peliculas\Pelicula;

function detallesCompra($idPelicula, $precio) {

    $compra = visualizarElemento($idPelicula, $precio);

    $html = <<<EOS
    <div class="detalles-compra">
    $compra
    </div>
    EOS;
    return $html;
}

function visualizarElemento($idPelicula, $precio) {

    $pelicula = Pelicula::buscaPorId($idPelicula);

    $html = <<<EOS
    <div class="elemento-compra">
        <h4>Titulo:{$pelicula->titulo}</h4>
        <p>Precio: {$precio}</p>
    </div>
    EOS;
    return $html;
}
