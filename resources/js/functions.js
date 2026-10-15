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
