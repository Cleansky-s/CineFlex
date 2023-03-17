<?php
require_once 'includes/config.php';
require_once 'includes/vistas/helpers/usuarios.php';

logout();

Utils::redirige(Utils::buildUrl('login.php'));
