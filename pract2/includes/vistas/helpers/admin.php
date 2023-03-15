<?php



function listaUsuarios()
{
    $usuarios = Usuario::devolverUsuariosConRol();

    if (count($usuarios) == 0) {
        return '';
    }

    $tituloRoles = Usuario::getRoleTitles();

    $html = "<div class='usuarios'>";
    foreach($usuarios as $usuario) {
        $html .= visualizaUsuario($usuario, $tituloRoles);
    }
    $html .="</div>";
    return $html;
}

function visualizaUsuario($usuario, $tituloRoles)
{
    $html = <<<EOS
    <div class="usuario">
        <h4>{$usuario->nombre}</h4>
        <p>Id: {$usuario->id}</p>
        <p>Username: {$usuario->nombreUsuario}</p>
        <p>Roles:</p>
    EOS;

    foreach($usuario->roles as $rol) {
        $html .= "<p>{$tituloRoles[$rol-1]}</p>";
    }

    $html .= botonEditarUsuario($usuario);
    $html .= "</div>";

    return $html;
}


function botonEditarUsuario($usuario){
    $editaURL = Utils::buildUrl('usuarios/editarRolesUsuario.php', [
        'id' => $usuario->id
    ]);
    return <<<EOS
    <a class="boton" href="{$editaURL}">Editar Roles</a>
    EOS;
}

function rolesUsuarioForm(Usuario $usuario)
{
    $proveedorValue = $usuario::PROVEEDOR_ROLE;
    $proveedorChecked = ($usuario->tieneRol($usuario::PROVEEDOR_ROLE)) ? "checked" : "";
    $moderadorValue = $usuario::MODERADOR_ROLE;
    $moderadorChecked = ($usuario->tieneRol($usuario::MODERADOR_ROLE)) ? "checked" : "";
    

    $htmlForm = <<<EOS
    <form action="actualizaRolesUsuario.php" method="POST">
        <input type="hidden" name="id" value="{$usuario->id}"/>
        <fieldset>
            <input type="checkbox" id="proveedor" name="proveedor" value="{$proveedorValue}" {$proveedorChecked} />
            <label for="proveedor"> Proveedor</label>
            <input type="checkbox" id="moderador" name="moderador" value="{$moderadorValue}" {$moderadorChecked}/>
            <label for="moderador"> Moderador</label>
            <div><button type="submit">Actualizar</button></div>
        </fieldset>
    </form>
    EOS;

    return $htmlForm;
}
