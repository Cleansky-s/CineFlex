<?php
require_once __DIR__.'/includes/config.php';


if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
    $app->redirige('/index.php');
}

$formLogout = new \es\ucm\fdi\aw\usuarios\FormularioLogout();
$formLogout->gestiona();