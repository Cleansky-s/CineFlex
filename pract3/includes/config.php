<?php

/**
 * Parámetros de conexión a la BD
 */
define('BD_HOST', 'localhost');
define('BD_NAME', 'cineflex');
define('BD_USER', 'cineflex');
define('BD_PASS', 'cineflex');

/**
 * Parámetros de configuración utilizados para generar las URLs y las rutas a ficheros en la aplicación
 */
define('RAIZ_APP', __DIR__);
define('RUTA_APP', '/CineFlex/pract3');
define('RUTA_IMGS', RUTA_APP.'img/');
define('RUTA_CSS', RUTA_APP.'css/');
define('RUTA_JS', RUTA_APP.'js/');

define('RUTA_ALMACEN', 'almacen');
define('RUTA_ALMACEN_PORTADAS', RUTA_ALMACEN . '/portadas');
define('RUTA_ALMACEN_TRAILERS', RUTA_ALMACEN . '/trailers');
define('RUTA_ALMACEN_PELICULAS', RUTA_ALMACEN . '/peliculas');

/**
 * Configuración del soporte de UTF-8, localización (idioma y país) y zona horaria
 */
ini_set('default_charset', 'UTF-8');
setLocale(LC_ALL, 'es_ES.UTF.8');
date_default_timezone_set('Europe/Madrid');

/**
 * Función para autocargar clases PHP.
 *
 * @see http://www.php-fig.org/psr/psr-4/
 */
spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'es\\ucm\\fdi\\aw\\';

    // base directory for the namespace prefix
    $base_dir = implode(DIRECTORY_SEPARATOR, [__DIR__, 'src', '']);

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

/* */
/* Inicialización de la aplicación */
/* */

define('INSTALADA', true);

$app = \es\ucm\fdi\aw\Aplicacion::getInstance();
$app->init(array('host'=>BD_HOST, 'bd'=>BD_NAME, 'user'=>BD_USER, 'pass'=>BD_PASS), RUTA_APP, RAIZ_APP);

if (! INSTALADA) {
	$app->paginaError(502, 'Error', 'Oops', 'La aplicación no está configurada. Tienes que modificar el fichero config.php');
}

/**
 * @see http://php.net/manual/en/function.register-shutdown-function.php
 * @see http://php.net/manual/en/language.types.callable.php
 */
register_shutdown_function(array($app, 'shutdown'));
