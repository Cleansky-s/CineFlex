<?php

require_once 'includes/config.php';

$tituloPagina = 'Portada';

$contenidoPrincipal=<<<EOS
<h1>Página de la biblioteca</h1>
	<p> Aquí estara el contenido de la biblioteca. </p>
EOS;


require 'includes/vistas/comun/layout.php';
