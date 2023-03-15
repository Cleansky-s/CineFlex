<?php

function buildFormularioLogin($username='', $password='')
{
    $procesaLogin = Utils::buildUrl('/login/procesarLogin.php');
    return <<<EOS
    <form id="formLogin" action="{$procesaLogin}" method="POST">
        <fieldset>
            <legend>Usuario y contraseña</legend>
            <div><label>Username:</label> <input type="text" name="username" value="$username" /></div>
            <div><label>Password:</label> <input type="password" name="password" password="$password" /></div>
            <div><button type="submit">Entrar</button></div>
        </fieldset>
    </form>
    EOS;
}

function buildFormularioRegister()
{
    $procesaRegister = Utils::buildUrl('/login/procesarRegister.php');
    return <<<EOS
    <form id="formLogin" action="{$procesaRegister}" method="POST">
        <fieldset>
            <legend>Nombre, usuario y contraseña</legend>
            <div><label>Name:</label> <input type="text" name="name" /></div>
            <div><label>Username:</label> <input type="text" name="username" /></div>
            <div><label>Password:</label> <input type="password" name="password" /></div>
            <div><button type="submit">Entrar</button></div>
        </fieldset>
    </form>
    EOS;
}