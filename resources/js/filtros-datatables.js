window.actualizarBadges = function (configFiltros, contenedorSelector = '#filtros_activos') {

    let contenedor = $(contenedorSelector);
    contenedor.empty();

    configFiltros.forEach(filtro => {

        let select = $(filtro.id);

        if (!select.length) return;

        let valor = select.val();
        let texto = select.find('option:selected').text();

        if (valor) {
            contenedor.append(`
                <span class="badge badge-institucional me-2 mb-1 text-wrap text-start" style="max-width:600px;">
                    ${filtro.nombre}: ${texto}
                    <span class="badge-close"
                          data-target="${filtro.id}"
                          style="cursor:pointer; margin-left:6px;">
                        ×
                    </span>
                </span>
            `);
        }
    });
}

$(document).on('click', '.badge-close', function () {
    let selector = $(this).data('target');
    $(selector).val(null).trigger('change');
});

window.limpiarFiltros = function (configFiltros, table = null) {

    if (!Array.isArray(configFiltros)) return;

    configFiltros.forEach(filtro => {
        let elemento = $(filtro.id);

        if (!elemento.length) return;

        elemento.val(null).trigger('change');
    });

    // Si existe DataTable, recargar
    if (table) {
        table.ajax.reload();
    }
}
