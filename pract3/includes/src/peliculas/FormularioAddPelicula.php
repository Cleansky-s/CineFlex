<?php

namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;

class FormularioAddPelicula extends Formulario {

    public function __construct() {
        parent::__construct('formPelicula', 
        [
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/proveerPeliculas.php')
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {
        $app = Aplicacion::getInstance();
        $idProveedor = $app->idUsuario();

        $titulo = $datos['titulo'] ?? '';
        $descripcion = $datos['descripcion'] ?? '';
        $generos = $datos['generos'] ?? [];
        $precioCompra = $datos['precioCompra'] ?? '';
        $precioAlquiler = $datos['precioAlquiler'] ?? '';
        $enSuscripcion = $datos['enSuscripcion'] ?? null;
        $visible = $datos['visible'] ?? null;
        $fechaCreacion = $datos['fechaCreacion'] ?? '';

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['titulo', 'descripcion', 'generos', 'precioCompra', 'precioAlquiler', 'fechaCreacion'], $this->errores, 'span', array('class' => 'error'));

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <input type="hidden" name="idProveedor" value="{$idProveedor} "/>
        <fieldset>
            <div>
                <label for="titulo">titulo:</label>
                <input type="text" name="titulo" required value="{$titulo}"/>
                {$erroresCampos['titulo']}
            </div>
            <div>
                <label for="descripcion">descripcion:</label>
                <textarea name="descripcion" rows=10 columns=100 required >{$descripcion}</textarea>
                {$erroresCampos['descripcion']}
            </div>
            <div>
            <label for="generos">generos:</label>
            <select name="generos[]" multiple required size = 14>
        EOS;

        foreach(Pelicula::GENEROS as $idGenero => $nombreGenero){
            $selected = (array_search($idGenero, $generos) !== false) ? 'selected' : '';
            $htmlForm .= "<option value='{$idGenero}' $selected>{$nombreGenero}</option>";
        }

        // no es posible poner el input file en predeterminado a como lo tenia antes la pelicula...


        $enSuscripcionChecked = ($enSuscripcion) ? 'checked' : "";
        $visibleChecked = ($visible) ? 'checked' : ""; 

        $htmlForm .= <<<EOS
                </select>
                {$erroresCampos['generos']}
            </div>
            <div>
                <label for="precioCompra">precioCompra:</label>
                <input type="number" step="0.01" max=99.99 min=0 name="precioCompra" value="{$precioCompra}" required/>
                {$erroresCampos['precioCompra']}
            </div>
            <div>
                <label for="precioAlquiler">precioAlquiler:</label>
                <input type="number" step="0.01" max=99.99 min=0 name="precioAlquiler" value="{$precioAlquiler}" required/>
                {$erroresCampos['precioAlquiler']}
            </div>
            <div>
                <input type="checkbox" name="enSuscripcion" value="enSuscripcion" $enSuscripcionChecked/>
                <label for="enSuscripcion">enSuscripcion</label>
            </div>

            <div>
                <input type="checkbox" name="visible" value="visible" $visibleChecked/>
                <label for="visible">visible</label>
            </div>
            <div>
                <label for="fechaCreacion" >Fecha de Salida:</label>
                <input type="date" name="fechaCreacion" value="$fechaCreacion"/>
                {$erroresCampos['fechaCreacion']}
            </div>
            <input type="submit" value="Continuar" />
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $idProveedor = $datos['idProveedor'];

        $titulo = filter_var($datos['titulo'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( ! $titulo ) {
            $this->errores['titulo'] = 'Es necesario el titulo.';
        }

        $descripcion = filter_var($datos['descripcion'], FILTER_SANITIZE_SPECIAL_CHARS);
        if ( ! $descripcion || mb_strlen($descripcion) < 10) {
            $this->errores['titulo'] = 'Añade una descripcion valida.';
        }
        
        $generos = $datos['generos'];
        if(count($generos)<1 || count($generos)>3){
            $this->errores['generos'] = "Es necesario escoger entre 1 y 3 generos.";
        }

        $precioCompra = $datos['precioCompra'];
        if($precioCompra<=0 || $precioCompra>20){
            $this->errores['precioCompra'] = "Introduce un precio valido.";
        }

        $precioAlquiler = $datos['precioAlquiler'];
        if($precioAlquiler<=0 || $precioAlquiler>20){
            $this->errores['precioAlquiler'] = "Introduce un precio valido.";
        }
        else if($precioAlquiler>=$precioCompra){
            $this->errores['precioAlquiler'] = "El precio de alquiler tiene que ser menor que el de compra.";
        }

        $fechaCreacion = $datos['fechaCreacion'];
        $today = date("Y-m-d");
        if($fechaCreacion>=$today){
            $this->errores['fechaCreacion'] = "{$fechaCreacion} >= {$today}";
        }

        $enSuscripcion = (isset($datos['enSuscripcion'])) ? 1 : 0;
        $visible = (isset($datos['visible'])) ? 1 : 0;

        
        if (count($this->errores) === 0) {
            $pelicula = Pelicula::buscaPorTituloExacto($titulo);
            
            if(!$pelicula) {
                $pelicula = Pelicula::crea($titulo, $descripcion, "tmp", "tmp", "tmp", $enSuscripcion, $fechaCreacion, $visible, $precioCompra, $precioAlquiler, 0, 0, $idProveedor, $generos);
                
                $urlRedireccion = \es\ucm\fdi\aw\Aplicacion::getInstance()->buildUrl('/editarPelicula.php',
                    ['id' => $pelicula->id, 'archivos' => 'true']);
                header("Location: {$urlRedireccion}");
                exit();
            }
            else {
                $this->errores[] = "El titulo ya existe.";
            }
        }
    }
}
