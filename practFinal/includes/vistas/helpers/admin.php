<?php

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\usuarios\Usuario;

function listaUsuarios()
{
    $usuarios = Usuario::devolverUsuarios();

    if (count($usuarios) == 0) {
        return '';
    }

    $tituloRoles = Usuario::getRoleTitles();

    $html = "<div class='user-list'>";
    foreach($usuarios as $usuario) {
        $html .= visualizaUsuario($usuario, $tituloRoles);
    }
    $html .="</div>";
    return $html;
}

function visualizaUsuario($usuario, $tituloRoles)
{
    $html = <<<EOS
    <div class="user-card">
        <h4>{$usuario->nombre}</h4>
        <p>Id: {$usuario->id}</p>
        <p>Username: {$usuario->nombreUsuario}</p>
        <p>Roles:</p>
    EOS;

    foreach($usuario->roles as $rol) {
        $html .= "<p>{$tituloRoles[$rol-1]}</p>";
    }
    if(!($usuario->tieneRol(Usuario::ADMIN_ROLE))){
        $html .= botonEditarUsuario($usuario);
    }
    $html .= "</div>";

    return $html;
}


function botonEditarUsuario($usuario){
    $editaURL = Aplicacion::getInstance()->buildUrl('usuarios/editarRolesUsuario.php', [
        'id' => $usuario->id
    ]);
    return <<<EOS
    <a href="{$editaURL}">Editar Roles</a>
    EOS;
}
