<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;
use es\ucm\fdi\aw\usuarios\FormularioLogout;

function mostrarSaludo()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if ($app->usuarioLogueado()) {
        $formLogout = new FormularioLogout();
        $htmlLogout = $formLogout->gestiona();
        $html = $htmlLogout;
    } else {
        $loginUrl = $app->resuelve('/login.php');
        $html = <<<EOS
        <a href="{$loginUrl}">Login</a>
      EOS;
    }

    return $html;
}

function mostrarProveedor() 
{
    $html = '';
    $app = Aplicacion::getInstance();
    if($app->tieneRol(Usuario::PROVEEDOR_ROLE))  {
        $urlProveerPelis = $app->buildUrl('/proveerPeliculas.php');
        $urlProveerCines = $app->buildUrl('/proveerCines.php');
        $html = <<<EOS
        <li><a href="{$urlProveerPelis}">Proveer Pelis</a></li>
        <li><a href="{$urlProveerCines}">Proveer Cines</a></li>
        EOS;
    }
    return $html;
}

function mostrarBiblioteca()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if($app->usuarioLogueado()) {
        $urlLogout = $app->buildUrl('/biblioteca.php');
        $html = <<<EOS
        <li><a href="{$urlLogout}">Biblioteca</a></li>
        EOS;
    }
    return $html;
}

function mostrarCarrito()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if($app->usuarioLogueado()) {
        $urlCarrito = $app->buildUrl('/carrito.php');
        $html = <<<EOS
        <li><a href="{$urlCarrito}">Carrito</a></li>
        EOS;
    }
    return $html;
}

function mostrarAdmin()
{
    $html = '';
    $app = Aplicacion::getInstance();
    if($app->tieneRol(Usuario::ADMIN_ROLE))  {
        $urlAdmin = $app->buildUrl('/admin.php');
        $html = <<<EOS
        <li><a href="{$urlAdmin}">Admin</a></li>
        EOS;
    }
    return $html;
}


// function mostrarLogin()
// {
//     $html = '';

//     if (estaLogado()) {
//         $urlLogout = Utils::buildUrl('/logout.php');
//         $html = <<<EOS
//         <li><a href="{$urlLogout}">Logout</a></li>
//         EOS;
//     } else {
//         $urlLogin = Utils::buildUrl('/login.php');
//         $html = <<<EOS
//         <li><a href="{$urlLogin}">Login</a><li>
//         EOS;
//     }

//     return $html;
// }

function mostrarLogo() {
    $app = Aplicacion::getInstance();
    $logoUrl = $app->buildUrl('/index.php');
    $logoSrc = $app->buildUrl('/img/CineFlex.png');
    return <<<EOS
    <div class="logo">
    <a href="{$logoUrl}"><img src="{$logoSrc}" alt = "CineFlex" /></a>
    </div>
    EOS;
}

function mostrarPrincipal() {
    $app = Aplicacion::getInstance();
    $urlPelis = $app->buildUrl('/index.php');
    $urlCines = $app->buildUrl('/cines_cartelera.php');
    $html = <<<EOS
    <li><a href="{$urlPelis}">Películas</a>
    <li><a href="{$urlCines}">Cines</a>
    EOS;   
    return $html; 
}

?>
<header>
    <?= mostrarLogo() ?>
	<nav>
		<ul class = "nav-links">
			<?= mostrarPrincipal() ?>
            <?= mostrarProveedor() ?>
			<?= mostrarAdmin() ?>
		</ul>
		<ul class = "nav-profile">
			<?= mostrarCarrito() ?>
			<?= mostrarBiblioteca() ?>
			<?= mostrarSaludo() ?>
		</ul>
	</nav>
</header>