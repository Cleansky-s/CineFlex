<?php

namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;

class FormularioLogout extends Formulario
{
    public function __construct() {
        parent::__construct('formLogout', [
            'action' =>  Aplicacion::getInstance()->resuelve('/logout.php'),
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/index.php')]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $camposFormulario = <<<EOS
            <button class="enlace" type="submit">Logout</button>
        EOS;
        return $camposFormulario;
    }

    /**
     * Procesa los datos del formulario.
     */
    protected function procesaFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();

        $app->logout();
        $mensajes = ['Hasta pronto !'];
        $app->putAtributoPeticion('mensajes', $mensajes);
        $result = $app->resuelve('/index.php');

        return $result;
    }
}
