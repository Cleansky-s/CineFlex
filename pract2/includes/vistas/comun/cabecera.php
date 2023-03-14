<?php
	session_start();
	require_once __DIR__.'/../helpers/usuarios.php';
?>
<header>
	<div>
		<a href="index.php">
		<img src="img/CineFlex.png" alt = "CineFlex" width = "100" />
		</a>
		<a href="index.php"> Películas </a>
		<a href="cines_cartelera.php"> Cines/Cartelera </a>
		<?php
			if(isset($_SESSION['login']) && (isset($_SESSION['esAdmin'] || isset($_SESSION['esProveedor'])))) {
				echo '<a href="proveerPeliculas.php"> Proveer peliculas </a>
				      <a href="proveerCines.php"> Proveer cines </a>';
			}
		?>
		
		<a href="biblioteca.php"> Biblioteca </a>
		<?= saludo() ?>
	</div>
	
</header>
