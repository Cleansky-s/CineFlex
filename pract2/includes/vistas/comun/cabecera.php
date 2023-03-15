<?php
	require_once __DIR__.'/../helpers/usuarios.php';
?>
<header>
	<div class="logo">
	<a href="<?= Utils::buildUrl('/index.php') ?>"><img src="<?= Utils::buildUrl('/img/CineFlex.png') ?>" alt = "CineFlex" /></a>
	</div>
	<nav>
		<ul class = "nav-links">
			<li><a href="<?= Utils::buildUrl('/index.php') ?>">Películas</a>
			<li><a href="<?= Utils::buildUrl('/cines_cartelera.php')?>">Cines</a>
			<?= mostrarProveedor() ?>
			<?= mostrarAdmin() ?>
		</ul>
	</nav>
	<div class = "nav-profile">
		<?= mostrarBiblioteca() ?>
		<?= mostrarLogin() ?>
	</div>

	
</header>
