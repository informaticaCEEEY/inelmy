/*
 * Helpers AJAX reutilizables (jQuery + SweetAlert2 + DataTables)
 *
 * Requisitos:
 *  - jQuery disponible como window.$ antes de ejecutar este archivo.
 *  - <meta name="csrf-token" content="{{ csrf_token() }}"> en el layout.
 *  - window.limpiarErrores($form) definida en functions.js.
 *  - Opcional: window.RUTA_LOGIN = "{{ route('login') }}"; en el layout
 *    (si no existe, se usa '/login').
 *
 * Funciones expuestas:
 *  - recargarTabla(tabla)
 *  - mostrarErrorAjax(xhr, opciones)
 *  - mostrarErroresValidacion(xhr, formulario)
 *  - manejarErrorAjax(xhr, opciones)
 *  - enviarFormulario(opciones)   -> crear / editar
 *  - eliminarRegistro(opciones)   -> DELETE con confirmación
 */

/* ------------------------------------------------------------------ */
/* Utilidades internas                                                 */
/* ------------------------------------------------------------------ */
const encabezadosAjax = () => ({
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'Accept': 'application/json'
});

window.limpiarErrores = function (formulario) {
    const $form = $(formulario);

    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('[data-error-for]').empty();
};

/* ------------------------------------------------------------------ */
/* Tablas                                                              */
/* ------------------------------------------------------------------ */

// Recarga un DataTable sin volver a la primera página.
window.recargarTabla = function (tabla) {
    if (
        tabla &&
        $(tabla).length &&
        $.fn.DataTable.isDataTable($(tabla)[0])
    ) {
        $(tabla).DataTable().ajax.reload(null, false);
    }
};

/* ------------------------------------------------------------------ */
/* Manejo de errores                                                   */
/* ------------------------------------------------------------------ */

// Muestra el aviso de error. Es la única dueña de los textos por defecto.
window.mostrarErrorAjax = function (xhr, opciones = {}) {
    const mensajes = {
        0:   'No hay conexión con el servidor.',
        403: 'No tienes permiso para realizar esta acción.',
        404: 'El registro solicitado no existe.',
        409: 'La acción no se puede completar por el estado actual del registro.',
        419: 'La sesión expiró. Recarga la página.',
        422: 'Los datos enviados no son válidos.',
        500: 'Ocurrió un error interno del servidor.'
    };

    const texto =
        opciones.mensaje ??
        mensajes[xhr.status] ??
        'Ocurrió un error. Intenta nuevamente.';

    console.error(
        opciones.log ?? 'Error en la petición AJAX:',
        xhr.status,
        xhr.responseJSON ?? xhr.responseText
    );

    return Swal.fire({
        title: opciones.titulo ?? 'Error',
        text: texto,
        icon: 'error'
    });
};

// Pinta los errores 422 de Laravel en los campos del formulario.
// Devuelve true si manejó el error.
window.mostrarErroresValidacion = function (xhr, formulario) {
    const $form = $(formulario);
    const errores = xhr.responseJSON?.errors;

    if (xhr.status !== 422 || !errores) {
        return false;
    }

    window.limpiarErrores($form);

    $.each(errores, function (campo, mensajes) {
        const $input = $form.find(':input').filter(function () {
            return this.name === campo;
        });

        const $contenedor = $form.find('[data-error-for]').filter(function () {
            return $(this).attr('data-error-for') === campo;
        });

        $input.addClass('is-invalid');
        $contenedor.text(mensajes?.[0] ?? 'El valor ingresado no es válido.');
    });

    $form.find('.is-invalid').first().trigger('focus');

    return true;
};

// Punto de entrada para cualquier error de una petición AJAX.
// Prioridad del mensaje: el que pide la vista > el del servidor (409/422)
// > el texto por defecto de mostrarErrorAjax.
window.manejarErrorAjax = function (xhr, opciones = {}) {
    const {
        $form = null,
        mensajes = {},
        alNoEncontrado = null,
        log = 'Error en petición AJAX'
    } = opciones;

    // 422 con formulario: errores junto a cada campo.
    if ($form && window.mostrarErroresValidacion(xhr, $form)) {
        return;
    }

    // Registro no encontrado: avisa y luego deja limpia la pantalla.
    if (xhr.status === 404) {
        window.mostrarErrorAjax(xhr, {
            mensaje: mensajes[404],
            log
        }).then(() => {
            if (typeof alNoEncontrado === 'function') {
                alNoEncontrado(xhr);
            }
        });

        return;
    }

    // Sesión vencida: lleva al login en lugar de mostrar un error genérico.
    if (xhr.status === 401) {
        Swal.fire({
            title: 'Sesión expirada',
            text: 'Vuelve a iniciar sesión para continuar.',
            icon: 'warning'
        }).then(() => {
            window.location.href = window.RUTA_LOGIN ?? '/login';
        });

        return;
    }

    // En 409 y 422 (reglas de negocio) se muestra el mensaje del servidor.
    // En otros códigos no, porque con APP_DEBUG puede traer detalles internos.
    const delServidor = [409, 422].includes(xhr.status)
        ? xhr.responseJSON?.message
        : undefined;

    window.mostrarErrorAjax(xhr, {
        mensaje: mensajes[xhr.status] ?? delServidor,
        log
    });
};

/* ------------------------------------------------------------------ */
/* Crear / editar                                                      */
/* ------------------------------------------------------------------ */

window.enviarFormulario = function ({
    $form,
    url,
    metodo = 'POST',
    modal = null,
    tabla = null,
    textoExito = 'Los cambios se guardaron correctamente.',
    mensajes = {},
    alExito = null,
    alNoEncontrado = null
}) {
    // Acepta tanto un objeto jQuery como un selector.
    $form = $form?.jquery ? $form : $($form);

    if (!$form.length || !$form[0]) {
        console.error('No se encontró el formulario.');
        return;
    }

    const verbo = metodo.toUpperCase();
    const datos = new FormData($form[0]);
    const $boton = $form.find('[type="submit"]');

    if (!['POST', 'PUT', 'PATCH'].includes(verbo)) {
        console.error(`Método no permitido en enviarFormulario: ${verbo}`);
        return;
    }

    // Laravel permite simular PUT, PATCH o DELETE mediante _method.
    if (verbo !== 'POST') {
        datos.set('_method', verbo);
    }

    // Quita errores de un intento anterior.
    window.limpiarErrores($form);

    $.ajax({
        url: url,
        method: 'POST',
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        headers: encabezadosAjax(),

        // Evita dobles envíos mientras dura la petición.
        beforeSend: () => $boton.prop('disabled', true),
        complete: () => $boton.prop('disabled', false),

        success: function (respuesta) {
            $form[0].reset();
            window.limpiarErrores($form);

            if (modal) {
                $(modal).modal('hide');
            }

            window.recargarTabla(tabla);

            Swal.fire({
                title: 'Éxito',
                text: textoExito,
                icon: 'success'
            });

            // Lógica particular de cada vista después de guardar.
            if (typeof alExito === 'function') {
                alExito(respuesta);
            }
        },

        error: function (xhr) {
            window.manejarErrorAjax(xhr, {
                $form,
                mensajes,
                log: 'Error al enviar el formulario',

                alNoEncontrado: function () {
                    $form[0].reset();
                    window.limpiarErrores($form);

                    if (modal) {
                        $(modal).modal('hide');
                    }

                    window.recargarTabla(tabla);

                    if (typeof alNoEncontrado === 'function') {
                        alNoEncontrado(xhr);
                    }
                }
            });
        }
    });
};

/* ------------------------------------------------------------------ */
/* Eliminar                                                            */
/* ------------------------------------------------------------------ */

window.eliminarRegistro = function ({
    url,
    tabla = null,
    textoConfirmar = '¡Esta acción no se puede revertir!',
    textoBoton = 'Sí, eliminar!',
    textoExito = 'El registro ha sido eliminado.',
    mensajes = {},
    alExito = null,
    alNoEncontrado = null
}) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: textoConfirmar,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: textoBoton,
        cancelButtonText: 'No, cancelar!'
    }).then((resultado) => {
        if (!resultado.isConfirmed) {
            return;
        }

        $.ajax({
            url: url,
            method: 'DELETE',
            dataType: 'json',
            headers: encabezadosAjax(),

            success: function (respuesta) {
                window.recargarTabla(tabla);

                Swal.fire({
                    title: 'Eliminado',
                    text: textoExito,
                    icon: 'success'
                });

                if (typeof alExito === 'function') {
                    alExito(respuesta);
                }
            },

            error: function (xhr) {
                window.manejarErrorAjax(xhr, {
                    mensajes,
                    log: 'Error al eliminar el registro',

                    alNoEncontrado: function () {
                        window.recargarTabla(tabla);

                        if (typeof alNoEncontrado === 'function') {
                            alNoEncontrado(xhr);
                        }
                    }
                });
            }
        });
    });
};
