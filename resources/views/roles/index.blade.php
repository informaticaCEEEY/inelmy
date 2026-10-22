
@extends('layouts.app')

@push('styles')
    @vite('resources/css/navbar.css')
    @vite('resources/css/dashboard.css')
    @vite('resources/css/datatable.css')
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.7/r-3.0.8/datatables.min.css" rel="stylesheet" integrity="sha384-BRRIJxYmCe3t9liG1Pi7Ufg2cB0SZg0eK/gaUhI/taUrJ4eHZJDmI7kkUm343vK3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('title', 'Catálogo de roles')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">
    <div class="catalogo-compacto">
        {{-- Encabezado --}}
        <div class="d-flex flex-wrap justify-content-between
                    align-items-center gap-3 mb-4">

            <div>
                <h1 class="h3 mb-1">Catálogo de roles</h1>
                <p class="text-muted mb-0">
                    Administración de roles.
                </p>
            </div>
            @can('roles.administrar')
                <button class="btn btn-inelmy" onclick="window.agregarRegistro('#form-rol', '#formRolModal')">
                    <i class="bi bi-person-plus me-1"></i>
                    Nuevo rol
                </button>
            @endcan
        </div>
        {{-- Tabla de roles --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="roles-table-wrapper">
                    <div class="datatable-compacta">
                        <table id="tabla-rol" class="display">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th scope="col" class="text-center">Permisos</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        {{-- Modal para agregar y editar roles --}}
        <div class="modal fade modal-inelmy" id="formRolModal" tabindex="-1"
            aria-labelledby="formRolModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">

                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="formRolModalLabel">
                            <i class="bi bi-person-plus me-2"></i>
                            <span id="labelForm">Agregar</span> rol
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    {{-- Formulario --}}
                    <form id="form-rol" method="POST">
                        @csrf
                        <input type="hidden" id="edit-id" name="id">

                        <div class="modal-body">
                            <div class="row g-3">
                                {{-- Nombre --}}
                                <div class="col-12 col-md-6">
                                    <label for="name" class="form-label">
                                        Nombre <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Ingrese el nombre" aria-describedby="error-name" required>

                                    <div id="error-name" class="invalid-feedback" data-error-for="name"></div>
                                </div>
                                {{-- Nombre --}}
                                <div class="col-12 col-md-12">
                                    <label for="permisos_id" class="form-label">
                                        Permisos <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select" name="permisos_id[]" id="permisos_id" multiple></select>

                                    <div id="error-permisos-id" class="invalid-feedback" data-error-for="permisos_id"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Pie del modal --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button type="submit" class="btn btn-primary" id="btnGuardarRol">
                                <i class="bi bi-check-lg me-1"></i> Guardar rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        {{-- Modal para visualizar permisos --}}
        <div class="modal fade modal-permisos" id="modalPermisosRol" tabindex="-1"
            aria-labelledby="modalPermisosRol" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPermisosRol">
                            <i class="bi bi-person-plus me-2"></i>
                            <span id="labelForm">Permisos del Rol</span> rol
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body" id="modalPermisosRolBody">
                        Cargando...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

    {{-- Extensión Responsive --}}
    <script src="https://cdn.datatables.net/responsive/3.0.8/js/dataTables.responsive.min.js"></script>

    {{-- jQuery UI, solo si lo utilizas --}}
    <script
        src="https://code.jquery.com/ui/1.14.1/jquery-ui.min.js"
        crossorigin="anonymous">
    </script>

    {{-- Select2, solo si lo utilizas --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/es.js"></script>
    @vite('resources/js/functions.js')
    @vite('resources/js/ajax-helpers.js')
    {{-- Inicialización de DataTables --}}
    <script>

        let modoFormulario = 'crear';

        function mostrarRol(rol) {

            window.limpiarErrores('#form-rol');

            $('#labelForm').html('Editar');
            $('#edit-id').val(rol.id);
            $('#name').val(rol.name);

            const $select = $('#permisos_id');

            // Limpiar las opciones anteriores.
            $select.empty();

            // Agregar los permisos actuales del rol.
            (rol.permissions ?? []).forEach(function (item) {
                const option = new Option(item.text, item.id, true, true);
                $select.append(option);
            });

            // Actualizar la interfaz de Select2.
            $select.trigger('change');
        }

        $(document).ready(function () {

            $('#formRolModal').on('shown.bs.modal', function () {
                if (modoFormulario === 'crear') {
                    $('#permisos_id').val(null).trigger('change');
                }
            });

            $('#permisos_id').select2({
                dropdownParent: $('#formRolModal'),
                theme: "bootstrap-5",
                width: '100%',
                placeholder: 'Selecciona un permiso',
                closeOnSelect: false,
                allowClear: true,
                ajax: {
                    url: "{{ route('filtros.permisos') }}",
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;

                        return {
                            results: data.permisos,
                            pagination: {
                                more: data.more
                            }
                        };
                    }
                }
            });

            var table = $('#tabla-rol').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('roles.datatable') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columns: [
                    { data: "id", name: "permissions.id", visible: false, searchable: false },
                    { data: "name", name: "name" },
                    {
                        data: "total_permisos",
                        className: "text-center",
                        render: function (data) {
                            return `<span class="badge badge-institucional">${data}</span>`;
                        }
                    },
                    {
                        data: null, name: 'acciones', orderable: false,  searchable: false,
                        render: function (data, type, row) {
                            if (type !== 'display') {
                                return '';
                            }

                            let botones = '';

                            @can('roles.administrar')
                                botones += `
                                        <button class="btn btn-sm btn-info ver-permisos" data-id="${data.id}"
                                            title="Ver permisos" aria-label="Ver permisos">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    `;
                                botones += `
                                    <button class="btn btn-sm btn-success edit-btn" data-id="${data.id}"
                                        title="Editar rol" aria-label="Editar rol">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    `;
                                botones += `
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}"
                                            title="Eliminar rol" aria-label="Eliminar rol">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    `;
                            @endcan

                            return `
                                <div class="d-flex justify-content-center align-items-center gap-1">
                                    ${botones}
                                </div>
                            `;
                        }
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: -1 },
                    { "width": "15%", "targets": 2 }
                ],
                dom: 'frtip',
                language: {
                    search: 'Buscar:',
                    infoFiltered: '',
                    loadingRecords: "Cargando...",
                    emptyTable:'Sin información en la tabla',
                    infoEmpty: 'No hay registros',
                    info: 'Mostrando  _START_ al _END_ de _TOTAL_ _ENTRIES-TOTAL_',
                    entries: {
                        _: 'resultados',
                        1: 'resultado'
                    },
                },
                order: [
                    [1, 'asc'],
                ],
            });

            $(document).on('click', '.ver-permisos', function() {
                let id = $(this).data('id');
                $('#modalPermisosRolBody').html('Cargando...');
                $('#modalPermisosRol').modal('show');
                $.get(`/roles/${id}/permisos`, function (data) {
                    let html = '<ul class="list-group">';
                    data.forEach(function (permiso) {
                        html += `<li class="list-group-item">${permiso}</li>`;
                    });
                    html += '</ul>';
                    $('#modalPermisosRolBody').html(html);
                });
            });

            //Función para llenar formulario de edición
            $(document).on('click', '.edit-btn', function () {
                modoFormulario = 'editar';
                var role_id = $(this).data('id');
                $.ajax({
                    url: '{{ route('roles.buscar') }}',
                    method: 'post',
                    data: {
                        role_id: role_id
                    },
                    cache: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(xhr) {
                        mostrarRol(xhr);
                        window.limpiarErrores('#form-rol');
                        $('#formRolModal').modal('show');

                    },
                    error: function (xhr) {
                        const $form = $('#form-rol');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        window.manejarErrorAjax(xhr, {
                            mensajes: {
                                404: 'El rol que intentas consultar no existe.'
                            },
                            log: 'Error al consultar el rol'
                        });
                    }
                });
            });
        });

        //Función para agregar y actualizar roles
        $('#form-rol').submit(function (e) {
            e.preventDefault();
            const id = $('#edit-id').val();
            const esEdicion = id > 0;

            window.enviarFormulario({
                $form: $(this),
                url: esEdicion ? '{{ url('roles') }}/' + id : '{{ route('roles.crear') }}',
                metodo: esEdicion ? 'PUT' : 'POST',
                modal: '#formRolModal',
                tabla: '#tabla-rol',
                textoExito: esEdicion
                    ? 'El rol ha sido actualizado correctamente.'
                    : 'El rol ha sido agregado correctamente.',
                mensajes: { 404: 'Rol no encontrado!' }
            });
        });

        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');

            window.eliminarRegistro({
                url: '{{ route('roles.eliminar', ':id') }}'.replace(':id', id),
                tabla: '#tabla-rol',
                textoBoton: 'Sí, eliminar rol!',
                textoExito: 'El rol ha sido eliminado.',
                mensajes: { 404: 'El rol ya no existe.' }
            });
        });
    </script>
@endpush
