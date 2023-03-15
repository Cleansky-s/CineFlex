<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?= Utils::buildUrl('/css/estilo.css') ?>" />
	<title><?= $tituloPagina ?></title>
</head>
<body>
<?php

require('cabecera.php');

?>
<main>
	<article>
		<?= $contenidoPrincipal ?>
	</article>
</main>

</body>
</html>