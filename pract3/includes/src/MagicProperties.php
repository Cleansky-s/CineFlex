<?php
namespace es\ucm\fdi\aw;

/**
 * Añade métodos mágicos para que las propiedades utilicen getters y setters.
 * Si existen métodos <code>setPropiedad(x)</code> y <code>getPropiedad()</code> se puede hacer:
 * <ul>
 *  <li><code>$var->propiedad</code>, que equivale a <code>$var->getPropiedad()</code></li>
 *  <li><code>$var->propiedad = $valor</code>, que equivale a <code>$var->setPropiedad($valor)</code></li>
 * </ul>
 */
trait MagicProperties
{
    public function __get($property)
    {
        $methodName = 'get' . ucfirst($property);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        } else {
            throw new \Exception("La propiedad '$property' no está definida");
        }
    }

    public function __set($property, $value)
    {
        $methodName = 'set' . ucfirst($property);
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        } else {
            throw new \Exception("La propiedad '$property' no está definida");
        }
    }
}
