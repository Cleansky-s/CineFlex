<?php

namespace es\ucm\fdi\aw\peliculas;

use es\ucm\fdi\aw\Aplicacion;
use es\ucm\fdi\aw\Formulario;
use es\ucm\fdi\aw\peliculas\Pelicula;

class FormularioArchivos extends Formulario {

    public function __construct() {
        parent::__construct('formPelicula', 
        [
            'urlRedireccion' => Aplicacion::getInstance()->resuelve('/proveerPeliculas.php'),
            'enctype'=>'multipart/form-data'
        ]);
    }

    protected function generaCamposFormulario(&$datos)
    {

        $htmlErroresGlobales = self::generaListaErroresGlobales($this->errores);
        $erroresCampos = self::generaErroresCampos(['urlPortada', 'urlTrailer', 'urlPelicula'], $this->errores, 'span', array('class' => 'error'));

        $idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $pelicula = Pelicula::buscaPorId($idPelicula);
        $app = Aplicacion::getInstance();
        
        $rutaPortada = $app->buildUrl(RUTA_ALMACEN_PORTADAS . "/{$pelicula->urlPortada}");
        $rutaTrailer = $app->buildUrl(RUTA_ALMACEN_TRAILERS . "/{$pelicula->urlTrailer}");
        $rutaPelicula = $app->buildUrl(RUTA_ALMACEN_PELICULAS . "/{$pelicula->urlPelicula}");

        $urlAtributos = \es\ucm\fdi\aw\Aplicacion::getInstance()->buildUrl('/peliculas/editarPelicula.php',
        ['id' => $pelicula->id]);
    

        $htmlForm = <<<EOS
        $htmlErroresGlobales
        <fieldset>
            <div>
            <label for="urlPortada">urlPortada:</label>
            <input type="file" name="urlPortada" accept="image/*"/>
            <img src="$rutaPortada" alt="No se puede mostrar la portada" width=180 height=200>
            {$erroresCampos['urlPortada']}
            </div>
            <div>
            <label for="urlTrailer">urlTrailer:</label>
            <input type="file" name="urlTrailer" accept="video/*"/>
            <video width="320" height="240" controls muted>
                <source src="$rutaTrailer">
            No se puede mostrar el trailer
            </video>
            {$erroresCampos['urlTrailer']}
            </div>
            <div>
            <label for="urlPelicula">urlPelicula:</label>
            <input type="file" name="urlPelicula" accept="video/*"/>
            <video width="320" height="240" controls>
                <source src="$rutaPelicula">
            No se puede mostrar la pelicula
            </video>
            {$erroresCampos['urlPelicula']}
            </div>
            <a href="$urlAtributos">Volver a atributos</a>
            <input type="submit" value="Guardar" />
        </fieldset>
        EOS;

        return $htmlForm;
    }

    protected function procesaFormulario(&$datos)
    {
        $this->errores = [];

        $app= Aplicacion::getInstance();

        $idPelicula = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        $pelicula = Pelicula::buscaPorId($idPelicula);

        if ($_FILES['urlPortada']['error'] == UPLOAD_ERR_OK) {

            $nombre = basename($_FILES['urlPortada']['name']);
            $extension = pathinfo($nombre, PATHINFO_EXTENSION);
            if(!(array_search($extension, Pelicula::EXTENSIONES_PERMITIDAS_IMG) !== false)){
                $this->errores['urlPortada'] = "Extension no permitida.";
            }
            else {
                $tmp_name = $_FILES['urlPortada']['tmp_name'];
                $fichero = "{$pelicula->id}.{$extension}";
                $rutaPortada = $app->buildUrl(RUTA_ALMACEN_PORTADAS . "/{$fichero}");
                if(!move_uploaded_file($tmp_name, $rutaPortada)){
                    $this->errores['urlPortada'] = "Archivo no valido";
                }
                else{
                    $pelicula->urlPortada = $fichero;
                }
            }
        }
        if ($_FILES['urlTrailer'] && $_FILES['urlTrailer']['error'] == UPLOAD_ERR_OK) {
        
            $nombre = basename($_FILES['urlTrailer']['name']);
            $extension = pathinfo($nombre, PATHINFO_EXTENSION);
            if(!(array_search($extension, Pelicula::EXTENSIONES_PERMITIDAS_VIDEO) !== false)){
                $this->errores['urlTrailer'] = "Extension no permitida.";
            }
            else {
                $tmp_name = $_FILES['urlTrailer']['tmp_name'];
                $fichero = "{$pelicula->id}.{$extension}";
                $rutaTrailer = $app->buildUrl(RUTA_ALMACEN_TRAILERS . "/{$fichero}");
                if(!move_uploaded_file($tmp_name, $rutaTrailer)){
                    $this->errores['urlTrailer'] = "Archivo no valido";
                }
                else {
                    $pelicula->urlTrailer = $fichero;
                }
                
            }
            
            
        }
        if ($_FILES['urlPelicula'] && $_FILES['urlPelicula']['error'] == UPLOAD_ERR_OK) {
        
            $nombre = basename($_FILES['urlPelicula']['name']);
            $extension = pathinfo($nombre, PATHINFO_EXTENSION);
            if(!(array_search($extension, Pelicula::EXTENSIONES_PERMITIDAS_VIDEO) !== false)){
                $this->errores['urlPelicula'] = "Extension no permitida.";
            }
            else {
                $tmp_name = $_FILES['urlPelicula']['tmp_name'];
                $fichero = "{$pelicula->id}.{$extension}";
                $rutaPelicula = $app->buildUrl(RUTA_ALMACEN_PELICULAS . "/{$fichero}");
                if(!move_uploaded_file($tmp_name, $rutaPelicula)){
                    $this->errores['urlPelicula'] = "Archivo no valido";
                }
                else {
                    $pelicula->urlPelicula = $fichero;
                }
            }
        }
        
        // guardamos siempre.
        $pelicula->guarda();

    }
}
