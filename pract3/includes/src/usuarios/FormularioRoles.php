<?php

namespace es\ucm\fdi\aw\usuarios;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\usuarios\Usuario;

class FormularioRoles extends Formulario {


    public function __construct() {
        parent::__construct('formPelicula', 
        [
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/admin.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        // en este caso no hacemos uso de los datos ya que no puede haber errores.

        $idUsuario = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $usuario = Usuario::buscaPorId($idUsuario);

        $proveedorValue = $usuario::PROVEEDOR_ROLE;
        $proveedorChecked = ($usuario->tieneRol($usuario::PROVEEDOR_ROLE)) ? "checked" : "";
        $moderadorValue = $usuario::MODERADOR_ROLE;
        $moderadorChecked = ($usuario->tieneRol($usuario::MODERADOR_ROLE)) ? "checked" : "";

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['proveedor', 'moderador'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="id" value="{$usuario->id}"/>
        <fieldset>
            <h3>{$usuario->nombre}</h3>
            <h4>{$usuario->nombreUsuario}</h4>
            <p>Roles a modificar:</p>
            <div>
                <input type="checkbox" id="proveedor" name="proveedor" value="{$proveedorValue}" {$proveedorChecked} />
                <label for="proveedor"> Proveedor</label>
            </div>
            <div>
                <input type="checkbox" id="moderador" name="moderador" value="{$moderadorValue}" {$moderadorChecked}/>
                <label for="moderador"> Moderador</label>
            </div>
            <button type="submit">Actualizar</button>
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $idUsuario = filter_var($datos['id'], FILTER_SANITIZE_NUMBER_INT);

        $rolProveedor = filter_var($datos['proveedor'], FILTER_SANITIZE_NUMBER_INT);

        $rolModerador = filter_var($datos['moderador'], FILTER_SANITIZE_NUMBER_INT);

        $usuario = Usuario::buscaPorId($idUsuario);

        $roles = [];

        $roles[] = $usuario::USER_ROLE;

        if($rolProveedor) {
            $roles[] = $rolProveedor;
        }

        if($rolModerador) {
            $roles[] = $rolModerador;
        }

        $usuario->roles = $roles;

        $usuario->guarda();

    }
}
