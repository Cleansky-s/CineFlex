<?php
require_once 'autorizacion.php';

function mostrarLogin()
{
    $html = '';

    if (estaLogado()) {
        $urlLogout = Utils::buildUrl('/logout.php');
        $html = <<<EOS
        <a href="{$urlLogout}">Logout</a>
        EOS;
    } else {
        $urlLogin = Utils::buildUrl('/login.php');
        $html = <<<EOS
        <a href="{$urlLogin}">Login</a>
        EOS;
    }

    return $html;
}

function mostrarBiblioteca()
{
    $html = '';
    if (estaLogado()) {
        $urlLogout = Utils::buildUrl('/biblioteca.php');
        $html = <<<EOS
        <a href="{$urlLogout}">Biblioteca</a>
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
        <li><a href="{$urlProveerPelis}">Proveer Pelis</a></li>
        <li><a href="{$urlProveerCines}">Proveer Cines</a></li>
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
        <li><a href="{$urlAdmin}">Admin</a></li>
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
