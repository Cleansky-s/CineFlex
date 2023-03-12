<?php

function buildHiddenFormParams($params) {
    $formParams='';
    foreach($params as $param => $value) {
        if ($value != null) {
            $formParams .= <<<EOS
            <input type="hidden" name="{$param}" value="{$value}" />
            EOS;
        }
    }
    return $formParams;
}

function buildButtonForm($url, $hiddenParams, $buttonText='Enviar', $formTagAtts = [], $method = 'POST')
{
    $formTagAtts = array_merge($formTagAtts, [
        'action' => $url,
        'method'=> $method
    ]);

    $formAtts = Utils::buildParams($formTagAtts, ' ', '"');
    $hiddenFormParams = buildHiddenFormParams($hiddenParams);
    return <<<EOS
    <form {$formAtts}>
        {$hiddenFormParams}
        <button type="submit">{$buttonText}</button>
    </form>
    EOS;
}

function visualizaMensaje($mensaje)
{
    $verURL = Utils::buildUrl('mensajes/mensajes.php', [
        'id' => $mensaje->id
    ]);
    return <<<EOS
    <a href="{$verURL}">{$mensaje->mensaje} ({$mensaje->autor?->nombreUsuario}) ({$mensaje->fechaYHora})</a>
    EOS;
}

function botonEditaMensaje($mensaje, $idMensajeRetorno = null)
{
    $editaURL = Utils::buildUrl('mensajes/editarMensaje.php', [
        'id' => $mensaje->id,
        'idMensajeRetorno' => $idMensajeRetorno
    ]);
    return <<<EOS
    <a class="boton" href="{$editaURL}">Editar</a>
    EOS;
}

function botonBorraMensaje($mensaje, $idMensajeRetorno = null)
{
    $borraURL = Utils::buildUrl('mensajes/borraMensaje.php');
    return buildButtonForm($borraURL, ['id' => $mensaje->id, 'idMensajeRetorno' => $idMensajeRetorno], 'Borrar', ['class' => 'inline']);
}

// XXX Esta función es muy similar a la funcion listaMensajesPaginados y en un proyecto real sólo debería de existir una de ellas
function listaMensajes($id = NULL, $recursivo = false, $idMensajeRetorno = null)
{
    $mensajes = Mensaje::buscaPorMensajePadre($id);
    if (count($mensajes) == 0) {
        return '';
    }

    $html = '<ul>';
    foreach($mensajes as $mensaje) {
        $html .= '<li>';
        $html .= visualizaMensaje($mensaje);
        if (estaLogado() && (esMismoUsuario($mensaje->idAutor) || esAdmin())) {
            $html .= botonEditaMensaje($mensaje, $idMensajeRetorno);
            $html .= botonBorraMensaje($mensaje, $idMensajeRetorno);
        }
        if ($recursivo) {
            $html .= listaMensajes($mensaje->getId(), $recursivo, $idMensajeRetorno);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';

    return $html;
}

function listaMensajesPaginados($id = null, $recursivo = false, $idMensajeRetorno = null, $numPorPagina = 5, $numPagina = 1)
{
    return listaMensajesPaginadosRecursivo($id, $recursivo, $idMensajeRetorno, 1, $numPorPagina, $numPagina);
}

function listaListaMensajesPaginados($mensajes, $recursivo = false, $idMensajeRetorno = null, $url='mensajes/mensajes.php', $extraUrlParams = [], $numPorPagina = 5, $numPagina = 1)
{
    return listaListaMensajesPaginadosRecursivo($mensajes, $recursivo, $idMensajeRetorno, $url, $extraUrlParams, 1, $numPorPagina, $numPagina);
}

function listaMensajesPaginadosRecursivo($id = null, $recursivo = false, $idMensajeRetorno = null, $nivel = 1, $numPorPagina = 5, $numPagina = 1)
{
    $mensajes = Mensaje::buscaPorMensajePadrePaginado($id, $numPorPagina+1, $numPagina-1);
    return listaListaMensajesPaginadosRecursivo($mensajes, $recursivo, $idMensajeRetorno, 'mensajes/mensajes.php', [], $nivel, $numPorPagina, $numPagina);
}

function listaListaMensajesPaginadosRecursivo($mensajes, $recursivo = false, $idMensajeRetorno = null, $url='mensajes/mensajes.php', $extraUrlParams = [], $nivel = 1, $numPorPagina = 5, $numPagina = 1)
{
    $numMensajes = count($mensajes);
    if ($numMensajes == 0) {
        return '';
    }

    $haySiguientePagina = false;
    if ($numMensajes > $numPorPagina) {
        $numMensajes = $numPorPagina;
        $haySiguientePagina = true;
    }

    $html = '<ul>';
    for($idx = 0; $idx < $numMensajes; $idx++) {
        $mensaje = $mensajes[$idx];
        $html .= '<li>';
        $html .= visualizaMensaje($mensaje);
        if (estaLogado() && (esMismoUsuario($mensaje->idAutor) || esAdmin())) {
            $html .= botonEditaMensaje($mensaje, $idMensajeRetorno);
            $html .= botonBorraMensaje($mensaje, $idMensajeRetorno);
        }
        if ($recursivo) {
            $html .= listaMensajesPaginadosRecursivo($mensaje->id, $recursivo, $idMensajeRetorno, $nivel+1, $numPagina, $numPorPagina);
        }
        $html .= '</li>';
    }
    $html .= '</ul>';

    if ($nivel == 1) {
        // Controles de paginacion
        $clasesPrevia='deshabilitado';
        $clasesSiguiente = 'deshabilitado';
        $hrefPrevia = '';
        $hrefSiguiente = '';

        if ($numPagina > 1) {
            // Seguro que hay mensajes anteriores
            $paginaPrevia = $numPagina - 1;
            $clasesPrevia = '';
            $hrefPrevia = Utils::buildUrl($url, array_merge($extraUrlParams, [
                'id' => $idMensajeRetorno,
                'numPagina' => $paginaPrevia,
                'numPorPagina' => $numPorPagina
            ]));
        }

        if ($haySiguientePagina) {
            // Puede que haya mensajes posteriores
            $paginaSiguiente = $numPagina + 1;
            $clasesSiguiente = '';
            $hrefSiguiente = Utils::buildUrl($url, array_merge($extraUrlParams, [
                'id' => $idMensajeRetorno,
                'numPagina' => $paginaSiguiente,
                'numPorPagina' => $numPorPagina
            ]));
        }

        $html .=<<<EOS
            <div>
                Página: $numPagina, <a class="boton $clasesPrevia" href="$hrefPrevia">Previa</a><a class="boton $clasesSiguiente" href="$hrefSiguiente">Siguiente</a>
            </div>
        EOS;
    }

    return $html;
}

function mensajeForm($action, $label, $button, $idMensajeRetorno = null, $idMensajeActualizar = null, $mensajeActual='')
{
    $mensajeRetorno = '';
    if ($idMensajeRetorno != null) {
        $mensajeRetorno = <<<EOS
        <input type="hidden" name="idMensajeRetorno" value="{$idMensajeRetorno}" />
        EOS;
    }
    $mensajeActualizar = '';
    if ($idMensajeActualizar != null) {
        $mensajeActualizar = <<<EOS
        <input type="hidden" name="id" value="{$idMensajeActualizar}" />
        EOS;
    }

    $htmlForm = <<<EOS
    <form action="{$action}" method="POST">
        $mensajeActualizar
        $mensajeRetorno
        <fieldset>
            <div><label for="mensaje">{$label}</label><input id="mensaje" type="text" name="mensaje" value="{$mensajeActual}" /></div>
            <div><button type="submit">{$button}</button></div>
        </fieldset>
    </form>
    EOS;

    return $htmlForm;
}
