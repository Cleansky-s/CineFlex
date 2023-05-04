<?php
namespace es\ucm\fdi\aw;

/**
 * Clase base para la gestión de formularios.
 */
abstract class Formulario
{

    /**
     * Genera la lista de mensajes de errores globales (no asociada a un campo) a incluir en el formulario.
     *
     * @param string[] $errores (opcional) Array con los mensajes de error de validación y/o procesamiento del formulario.
     *
     * @param string $classAtt (opcional) Valor del atributo class de la lista de errores.
     *
     * @return string El HTML asociado a los mensajes de error.
     */
    protected static function generaListaErroresGlobales($errores = array(), $classAtt = '')
    {
        $clavesErroresGlobales = array_filter(array_keys($errores), function ($elem) {
            return is_numeric($elem);
        });

        $numErrores = count($clavesErroresGlobales);
        if ($numErrores == 0) {
            return '';
        }

        $html = "<ul class=\"$classAtt\">";
        foreach ($clavesErroresGlobales as $clave) {
            $html .= "<li>$errores[$clave]</li>";
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Crea una etiqueta para mostrar un mensaje de error. Sólo creará el mensaje de error
     * si existe una clave <code>$idError</code> dentro del array <code>$errores</code>.
     * 
     * @param string[] $errores     (opcional) Array con los mensajes de error de validación y/o procesamiento del formulario.
     * @param string   $idError     (opcional) Clave dentro de <code>$errores</code> del error a mostrar.
     * @param string   $htmlElement (opcional) Etiqueta HTML a crear para mostrar el error.
     * @param array    $atts        (opcional) Tabla asociativa con los atributos a añadir a la etiqueta que mostrará el error.
     */
    protected static function createMensajeError($errores = [], $idError = '', $htmlElement = 'span', $atts = [])
    {
        if (! isset($errores[$idError])) {
            return '';
        }

        $att = '';
        foreach ($atts as $key => $value) {
            $att .= "$key=\"$value\" ";
        }
        $html = "<$htmlElement $att>{$errores[$idError]}</$htmlElement>";

        return $html;
    }

    protected static function generaErroresCampos($campos, $errores, $htmlElement = 'span', $atts = []) {
        $erroresCampos = [];
        foreach($campos as $campo) {
            $erroresCampos[$campo] = self::createMensajeError($errores, $campo, $htmlElement, $atts);
        }
        return $erroresCampos;
    }

    /**
     * @var string Parámetro de la petición utilizado para comprobar que el usuario ha enviado el formulario.
     */
    private $tipoFormulario;

    /**
     * @var string Identificador utilizado para construir el atributo "id" de la etiqueta &lt;form&gt; como <code>$tipoFormulario.$formId</code>.
     */
    private $formId;

    /**
     * @var string Método HTTP utilizado para enviar el formulario.
     */
    protected $method;

    /**
     * @var string URL asociada al atributo "action" de la etiqueta &lt;form&gt; del fomrulario y que procesará el 
     * envío del formulario.
     */
    protected $action;

    /**
     * @var string Valor del atributo "class" de la etiqueta &lt;form&gt; asociada al formulario. Si este parámetro incluye la cadena "nocsrf" no se generá el token CSRF para este formulario.
     */
    protected $classAtt;

    /**
     * @var string Valor del parámetro enctype del formulario.
     */
    protected $enctype;

    /**
     * @var string Url a la que redirigir en caso de que el formulario se procese exitosamente.
     */
    protected $urlRedireccion;

    /**
     * @param string[] Array con los mensajes de error de validación y/o procesamiento del formulario.
     */
    protected $errores;

    /**
     * Crea un nuevo formulario.
     *
     * Posibles opciones:
     * <table>
     *   <thead>
     *     <tr>
     *       <th>Opción</th>
     *       <th>Valor por defecto</th>
     *       <th>Descripción</th>
     *     </tr>
     *   </thead>
     *   <tbody>
     *     <tr>
     *       <td>action</td>
     *       <td><code>$_SERVER['REQUEST_URI']</code></td>       
     *       <td>URL asociada al atributo "action" de la etiqueta &lt;form&gt; del formulario y que procesará el envío del formulario.</td>
     *     </tr>
     *     <tr>
     *       <td>class</td>
     *       <td><code>null</code></td>       
     *       <td>Valor del atributo "class" de la etiqueta &lt;form&gt; asociada al formulario. Si este parámetro incluye la cadena
     *        "nocsrf" no se generá el token CSRF para este formulario.</td>
     *     </tr>
     *     <tr>
     *       <td>enctype</td>
     *       <td><code>null</code></td>       
     *       <td>Valor del parámetro enctype del formulario.</td>
     *     </tr>
     *     <tr>
     *       <td>method</td>
     *       <td>POST</td>       
     *       <td>Método HTTP para enviar el formulario (e.g. 'POST', 'GET').</td>
     *     </tr>
     *     <tr>
     *       <td>urlRedireccion</td>
     *       <td><code>null/code></td>       
     *       <td>Url a la que redirigir en caso de que el formulario se procese exitosamente.</td>
     *     </tr>
     *     <tr>
     *       <td>formId</td>
     *       <td><code>''/code></td>       
     *       <td>Identificador utilizado para construir el atributo "id" de la etiqueta &lt;form&gt; como <code>$tipoFormulario.$formId</code>.</td>
     *     </tr>
     *   </tbody>
     * </table>
     *
     * @param string $tipoFormulario Parámetro de la petición utilizado para comprobar que el usuario ha enviado el formulario.
     * @param array $opciones (opcional) Array de opciones para el formulario (ver más arriba).
     */
    public function __construct($tipoFormulario, $opciones = array())
    {
        $this->tipoFormulario = $tipoFormulario;

        $opcionesPorDefecto = array('action' => null, 'method' => 'POST', 'class' => null, 'enctype' => null, 'urlRedireccion' => null, 'formId' => '');
        $opciones = array_merge($opcionesPorDefecto, $opciones);

        $this->formId = $tipoFormulario.$opciones['formId'];
        $this->action = $opciones['action'];
        $this->method = $opciones['method'];
        $this->classAtt = $opciones['class'];
        $this->enctype  = $opciones['enctype'];
        $this->urlRedireccion = $opciones['urlRedireccion'];

        if (!$this->action) {
            $this->action = htmlspecialchars($_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Se encarga de orquestar todo el proceso de gestión de un formulario.
     * 
     * El proceso es el siguiente:
     * <ul>
     *   <li>O bien se quiere mostrar el formulario </li>
     *   <li>O bien hay que procesar el formulario con dos resultados:
     *     <ul>
     *       <li>El formulario se ha procesado correctamente y se devuelve un <code>string</code> en {@see Form::procesaFormulario()}
     *           que será la URL a la que se redirigirá al usuario. Se redirige al usuario y se termina la ejecución del script.</li>
     *       <li>El formulario NO se ha procesado correctamente (errores en los datos, datos incorrectos, etc.) y se devuelve
     *           un <code>array</code> con entradas (campo, mensaje) con errores específicos para un campo o (entero, mensaje) si el mensaje
     *           es un mensaje que afecta globalmente al formulario. Se vuelve a generar el formulario pasándole el array de errores.</li> 
     *     </ul>
     *   </li>
     * </ul>
     */
    public function gestiona()
    {
        $datos = &$_POST;
        if (strcasecmp('GET', $this->method) == 0) {
            $datos = &$_GET;
        }
        $this->errores = [];

        if (!$this->formularioEnviado($datos)) {
            return $this->generaFormulario();
        }

        $this->procesaFormulario($datos);
        $esValido = count($this->errores) === 0;

        if (! $esValido ) {
            return $this->generaFormulario($datos);
        }

        if ($this->urlRedireccion !== null) {
            header("Location: {$this->urlRedireccion}");
            exit();
        }
    }

    /**
     * Genera el HTML necesario para presentar los campos del formulario.
     * 
     * Si el formulario ya ha sido enviado y hay errores en {@see Form::procesaFormulario()} se llama a este método
     * nuevamente con los datos que ha introducido el usuario en <code>$datos</code> y los errores al procesar
     * el formulario en <code>$errores</code>
     *
     * @param string[] &$datos Datos iniciales para los campos del formulario (normalmente <code>$_POST</code>).
     *
     * @return string HTML asociado a los campos del formulario.
     */
    protected function generaCamposFormulario(&$datos)
    {
        return '';
    }

    /**
     * Procesa los datos del formulario.
     *
     * @param string[] $datos Datos enviado por el usuario.
     *
     */
    protected function procesaFormulario(&$datos)
    {
    }

    /**
     * Función que verifica si el usuario ha enviado el formulario.
     * 
     * Comprueba si existe el parámetro <code>$formId</code> en <code>$datos</code>.
     *
     * @param string[] &$datos Array que contiene los datos recibidos en el envío formulario.
     *
     * @return boolean Devuelve <code>true</code> si <code>$formId</code> existe como clave en <code>$datos</code>
     */
    protected function formularioEnviado(&$datos)
    {
        return isset($datos['tipoFormulario']) && $datos['tipoFormulario'] == $this->tipoFormulario;
    }

    /**
     * Función que genera el HTML necesario para el formulario.
     *
     * @param string[] &$datos (opcional) Array con los valores por defecto de los campos del formulario.
     *
     * @return string HTML asociado al formulario.
     */
    protected function generaFormulario(&$datos = array())
    {
        $htmlCamposFormularios = $this->generaCamposFormulario($datos);

        $classAtt = $this->classAtt != null ? "class=\"{$this->classAtt}\"" : '';

        $enctypeAtt = $this->enctype != null ? "enctype=\"{$this->enctype}\"" : '';

        $htmlForm = <<<EOS
        <form method="{$this->method}" action="{$this->action}" id="{$this->formId}" {$classAtt} {$enctypeAtt}>
            <input type="hidden" name="tipoFormulario" value="{$this->tipoFormulario}" />
            $htmlCamposFormularios
        </form>
        EOS;
        return $htmlForm;
    }
}
