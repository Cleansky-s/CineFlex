<?php
	require_once __DIR__.'/../helpers/usuarios.php';
?>
<header>
	<div class="icon">
		<a href="<?= Utils::buildUrl('/index.php') ?>"><img src="<?= Utils::buildUrl('/img/CineFlex.png') ?>" alt = "CineFlex" width = "100" /></a>
	</div>
	<div class="links">
		<div class = "left">
			<a href="<?= Utils::buildUrl('/index.php') ?>"> Películas </a>
			<a href="<?= Utils::buildUrl('cines_cartelera.php')?>"> Cines/Cartelera </a>
			<?= mostrarProveedor() ?>
			<?= mostrarAdmin() ?>
		</div>
				
		<div class = "right">
			<a href="biblioteca.php"> Biblioteca </a>
			<?= saludo() ?>
		</div>
	</div>
	
</header>
