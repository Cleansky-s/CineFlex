<?php

require_once __DIR__.'/../helpers/usuarios.php';

?>
<header>
	<div>
		<a href="index.php">
		<img src="img/CineFlex.png" alt = "CineFlex" width = "100" />
		</a>
		<a href="index.php"> Películas </a>
		<a href="cines_cartelera.php"> Cines/Cartelera </a>
		<a href="biblioteca.php"> Biblioteca </a>
		<?= saludo() ?>
	</div>
	
</header>
