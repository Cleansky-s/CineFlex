<?php
require_once 'autorizacion.php';

function saludo()
{
    $html = '';

    if (estaLogado()) {
        $urlLogout = Utils::buildUrl('/logout.php');
        $html = <<<EOS
        Bienvenido, {$_SESSION['nombre']} <a href="{$urlLogout}">(salir)</a>
        EOS;
    } else {
        $urlLogin = Utils::buildUrl('/login.php');
        $html = <<<EOS
        Usuario desconocido. <a href="{$urlLogin}">Login</a>
        EOS;
    }

    return $html;
}

function mostrarProveedor() 
{
    $html = '';
    if(esProveedor())  {
        $urlProveerPelis = Utils::buildUrl('/proveerPeliculas.php');
        $urlProveerCines = Utils::buildUrl('/proveerCines.php');
        $html = <<<EOS
        <a href="{$urlProveerPelis}">Proveer Pelis</a>
        <a href="{$urlProveerCines}">Proveer Cines</a>
        EOS;
    }
    return $html;
}

function mostrarAdmin()
{
    $html = '';
    if(esAdmin())  {
        $urlAdmin = Utils::buildUrl('/admin.php');
        $html = <<<EOS
        <a href="{$urlAdmin}">Admin</a>
        EOS;
    }
    return $html;
}

function logout()
{
    //Doble seguridad: unset + destroy
    unset($_SESSION['idUsuario']);
    unset($_SESSION['roles']);
    unset($_SESSION['nombre']);
    
    session_destroy();
    session_start();
}
