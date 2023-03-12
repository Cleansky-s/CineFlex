<?php

abstract class Utils
{
    private function __construct()
    {
    }

    public static function paginaError($codigoRespuesta, $tituloPagina, $mensajeError, $explicacion = '')
    {
        http_response_code($codigoRespuesta);
        $contenidoPrincipal = "<h1>{$mensajeError}</h1><p>{$explicacion}</p>";
        require dirname(__DIR__).'/vistas/comun/layout.php';
        exit();    
    }
    
    public static function redirige($url) 
    {
        header('Location: '.$url);
        exit();
    }
    
    public static function buildUrl($relativeURL, $params = [])
    {
        if ($relativeURL[0] != '/') {
            $relativeURL = '/'.$relativeURL;
        }
        $url = RUTA_APP.$relativeURL;
        $query = self::buildParams($params);
        if (!empty($query)) {
            $url .= '?'.$query;
        }
    
        return $url;
    }
    
    public static function buildParams($params, $separator='&', $enclosing='') {
        $query= '';
        $numParams = 0;
        foreach($params as $param => $value) {
            if ($value != null) {
                if ($numParams > 0) {
                    $query .= $separator;
                }
                $query .= "{$param}={$enclosing}{$value}{$enclosing}";
                $numParams++;
            }
        }
        return $query;
    }
}