<?php

require_once 'includes/config.php';

$tituloPagina = 'Portada';

$contenidoPrincipal=<<<EOS
<h1>Página de cines/cartelera</h1>
	<p> Aquí estará el contenido de los cines y peículas disponibles. </p>
EOS;

require 'includes/vistas/comun/layout.php';
