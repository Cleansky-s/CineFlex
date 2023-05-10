<?php

require_once 'includes/config.php';
require_once 'includes/vistas/helpers/peliculas.php';

$tituloPagina = 'Portada';

$contenidoPrincipal="<h1>Lista de todas las peliculas</h1>";

$contenidoPrincipal .= listaPeliculas();

require 'includes/vistas/comun/layout.php';
