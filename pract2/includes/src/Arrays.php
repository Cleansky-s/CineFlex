<?php

/**
 * Funciones de utilería para arrays.
 */
abstract class Arrays
{
    private function __construct()
    {
    }

    /**
     * Busca el primer elemento en un elemento en un array basado en el criterio establecido por la función que se pasa como parámetro.
     * 
     * @param array $haystack array de entrada.
     * @param callable $callback Función a la que se le pasa el valor y clave de cada posición del array (en ese orden) y debe devolver <code>true</code> si es el elemento buscado o <code>false</code> en otro caso.
     * 
     * @return int|string|false Devuelve la clave / posición del array donde se encuentra el elemento buscado o <code>false</code> en otro caso.
     */
    public static function find($haystack, $callback)
    {
        foreach ($haystack as $key => $value) {
            if (call_user_func($callback, $value, $key) === true) {
                return $key;
            }
        }
        return false;
    }
}
