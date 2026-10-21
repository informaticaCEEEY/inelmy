window.limpiarErrores = function (formulario) {
    const $form = $(formulario);

    $form.find('.is-invalid').removeClass('is-invalid');
    $form.find('[data-error-for]').empty();
};

window.agregarRegistro = function (formularioNombre, modalNombre, checkboxEmpty = false) {
    window.limpiarErrores(formularioNombre);
    modoFormulario = 'crear';
    $('#labelForm').html('Agregar');
    $(formularioNombre)[0].reset();
    $('#edit-id').val('');
    $(modalNombre).modal('show');
}

window.mostrarSelect2 = function (selector, id, texto) {

    let $select = $(selector);

    if (!id) {
        $select.val(null).trigger('change');
        return;
    }

    if ($select.find("option[value='" + id + "']").length) {
        $select.val(id).trigger('change');
    } else {
        let newOption = new Option(texto, id, true, true);
        $select.append(newOption).trigger('change');
    }
}

window.enviarFormulario = function ({ $form, url, metodo = 'POST', modal, tabla, textoExito, mensajes = {} }) {
    const datos = new FormData($form[0]);
    if (metodo !== 'POST') datos.append('_method', metodo);

    $.ajax({
        url, method: 'POST', data: datos,
        cache: false, contentType: false, processData: false, dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Accept': 'application/json'
        },
        success: function () {
            Swal.fire({ title: 'Éxito!', text: textoExito, icon: 'success' });
            $form[0].reset();
            window.limpiarErrores($form);
            $(modal).modal('hide');
            $(tabla).DataTable().ajax.reload();
        },
        error: function (xhr) {
            window.manejarErrorAjax(xhr, {
                $form, mensajes,
                alNoEncontrado: () => {
                    $form[0].reset();
                    $(modal).modal('hide');
                    $(tabla).DataTable().ajax.reload();
                }
            });
        }
    });
};

window.manejarErrorAjax = function (xhr, { $form = null, mensajes = {}, alNoEncontrado = null } = {}) {
    const textos = {
        0:   'No hay conexión con el servidor.',
        403: 'No tienes permiso para realizar esta acción.',
        404: 'El registro ya no existe.',
        419: 'La sesión expiró. Recarga la página.',
        500: 'Ocurrió un error en el servidor. Intenta nuevamente.',
        default: 'Ocurrió un error inesperado. Intenta nuevamente.',
    };

    if ($form) window.limpiarErrores($form);

    // Errores de validación: se pintan en el formulario
    if (xhr.status === 422 && xhr.responseJSON?.errors && $form) {
        $.each(xhr.responseJSON.errors, function (campo, msgs) {
            $form.find(':input').filter(function () { return this.name === campo; })
                .addClass('is-invalid');
            $form.find('[data-error-for]').filter(function () {
                return $(this).attr('data-error-for') === campo;
            }).text(msgs[0] ?? 'El valor ingresado no es válido.');
        });
        $form.find('.is-invalid').first().trigger('focus');
        return;
    }

    // El registro ya no existe: limpia la pantalla con lo que cada vista necesite
    if (xhr.status === 404 && typeof alNoEncontrado === 'function') alNoEncontrado();

    if (xhr.status >= 500 || !(xhr.status in textos)) {
        console.error('Error AJAX:', xhr.status, xhr.responseJSON ?? xhr.responseText);
    }

    Swal.fire({ title: 'Error!', text: textos[xhr.status] ?? textos.default, icon: 'error' });
};
