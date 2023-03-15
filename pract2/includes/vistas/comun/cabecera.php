<?php
	require_once __DIR__.'/../helpers/usuarios.php';
?>
<header>
	<div class="icon">
		<a href="index.php"><img src="img/CineFlex.png" alt = "CineFlex" width = "100" /></a>
	</div>
	<div class="links">
		<div class = "left">
			<a href="index.php"> Películas </a>
			<a href="cines_cartelera.php"> Cines/Cartelera </a>
			<?= mostrarProveedor() ?>
			<?= mostrarAdmin() ?>
		</div>
				
		<div class = "right">
			<a href="biblioteca.php"> Biblioteca </a>
			<?= saludo() ?>
		</div>
	</div>
	
</header>
