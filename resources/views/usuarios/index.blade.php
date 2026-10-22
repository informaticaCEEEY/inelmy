
@extends('layouts.app')

@push('styles')
    @vite('resources/css/navbar.css')
    @vite('resources/css/dashboard.css')
    @vite('resources/css/datatable.css')
    @vite('resources/css/filtros-datatables.css')
    <link href="https://cdn.datatables.net/v/dt/dt-2.3.7/r-3.0.8/datatables.min.css" rel="stylesheet" integrity="sha384-BRRIJxYmCe3t9liG1Pi7Ufg2cB0SZg0eK/gaUhI/taUrJ4eHZJDmI7kkUm343vK3" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('title', 'Catálogo de usuarios')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">

        <div>
            <h1 class="h3 mb-1">Catálogo de usuarios</h1>
            <p class="text-muted mb-0">
                Administración de usuarios y sus roles en INELMY.
            </p>
        </div>

        @if (Route::has('usuarios.crear'))
            @can('usuarios.crear')
                <button class="btn btn-inelmy" onclick="window.agregarRegistro('#form-usuario', '#formUsuarioModal')">
                    <i class="bi bi-person-plus me-1"></i>
                    Nuevo usuario
                </button>
            @endcan
        @endif
    </div>
    {{-- Tabla de usuarios --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="usuarios-table-wrapper">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Roles</label>
                        <select id="filtro_rol" class="form-control filtro"></select>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-9">
                        <div id="filtros_activos"></div>
                    </div>
                    <div class="col-md-3 text-end">
                        <button id="btn_limpiar_filtros" class="btn btn-outline-secondary btn-sm">
                            Limpiar filtros
                        </button>
                    </div>
                </div>
                <table id="tabla-usuario" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre Completo</th>
                            <th>Celular</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                            <th>Estatus</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        {{-- Modal para agregar y editar usuarios --}}
        <div class="modal fade modal-inelmy" id="formUsuarioModal" tabindex="-1"
            aria-labelledby="formUsuarioModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">

                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="formUsuarioModalLabel">
                            <i class="bi bi-person-plus me-2"></i>
                            <span id="labelForm">Agregar</span> usuario
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    {{-- Formulario --}}
                    <form id="form-usuario" method="POST">
                        @csrf
                        <input type="hidden" id="edit-id" name="id">

                        <div class="modal-body">
                            <div class="row g-3">
                                {{-- Nombre --}}
                                <div class="col-12 col-md-6">
                                    <label for="nombre" class="form-label">
                                        Nombre <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" id="nombre" name="nombre" class="form-control"
                                        placeholder="Ingrese el nombre" aria-describedby="error-nombre" required>

                                    <div id="error-nombre" class="invalid-feedback" data-error-for="nombre"></div>
                                </div>

                                {{-- Primer apellido --}}
                                <div class="col-12 col-md-6">
                                    <label for="primer_apellido" class="form-label">
                                        Primer apellido
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text" id="primer_apellido" name="primer_apellido"
                                        class="form-control" placeholder="Ingrese el primer apellido"
                                         aria-describedby="error-primer-apellido" required>

                                    <div id="error-primer-apellido" class="invalid-feedback" data-error-for="primer_apellido"></div>
                                </div>

                                {{-- Segundo apellido --}}
                                <div class="col-12 col-md-6">
                                    <label for="segundo_apellido" class="form-label">
                                        Segundo apellido
                                    </label>

                                    <input type="text" id="segundo_apellido" name="segundo_apellido"
                                        class="form-control" placeholder="Ingrese el segundo apellido"
                                        aria-describedby="error-segundo-apellido">

                                    <div id="error-segundo-apellido" class="invalid-feedback" data-error-for="segundo_apellido"></div>
                                </div>

                                {{-- CURP --}}
                                <div class="col-12 col-md-6">
                                    <label for="curp" class="form-label">
                                        CURP
                                    </label>

                                    <input type="text" id="curp" name="curp" class="form-control text-uppercase"
                                        placeholder="Ingrese la CURP" maxlength="18" aria-describedby="error-curp">

                                    <div id="error-curp" class="invalid-feedback" data-error-for="curp"></div>
                                </div>

                                {{-- Celular --}}
                                <div class="col-12 col-md-6">
                                    <label for="celular" class="form-label">
                                        Celular
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="tel" id="celular" name="celular" class="form-control"
                                        placeholder="Ingrese el celular" maxlength="10"
                                        aria-describedby="error-celular" required>

                                    <div id="error-celular" class="invalid-feedback" data-error-for="celular"></div>
                                </div>

                                {{-- Correo --}}
                                <div class="col-12 col-md-6">
                                    <label for="email" class="form-label">
                                        Correo electrónico
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="email" id="email" name="email"
                                        class="form-control" placeholder="ejemplo@correo.com"
                                        aria-describedby="error-email" required>

                                    <div id="error-email" class="invalid-feedback" data-error-for="email"></div>
                                </div>

                                {{-- Contraseña --}}
                                <div class="col-12 col-md-6">
                                    <label for="password" class="form-label">
                                        Contraseña
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="password" id="password" name="password"
                                        class="form-control" aria-describedby="error-password">

                                    <div id="error-password" class="invalid-feedback" data-error-for="password"></div>
                                </div>

                                {{-- Confirmar Contraseña --}}
                                <div class="col-12 col-md-6">
                                    <label for="password_confirmation" class="form-label">
                                        Confirmar Contraseña
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control" aria-describedby="error-password-confirmation">

                                    <div id="error-password-confirmation" class="invalid-feedback" data-error-for="password_confirmation"></div>
                                </div>

                                {{-- Rol --}}
                                <div class="col-12 col-md-6">
                                    <label for="role_id" class="form-label">
                                        Rol
                                        <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select" name="role_id" id="role_id" required>
                                        <option value="">Seleccione un rol</option>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id }}">{{ $rol->name }}</option>
                                        @endforeach
                                    </select>

                                    <div id="error-role-id" class="invalid-feedback" data-error-for="role_id"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Pie del modal --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button type="submit" class="btn btn-primary" id="btnGuardarUsuario">
                                <i class="bi bi-check-lg me-1"></i> Guardar usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal para actualizar contraseña --}}
        <div class="modal fade modal-inelmy" id="formPasswordModal" tabindex="-1"
            aria-labelledby="formPasswordModal" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">

                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="formPasswordModal">
                            <i class="bi bi-person-plus me-2"></i>
                            Actualizar contraseña
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    {{-- Formulario --}}
                    <form id="form-password" method="POST">
                        @csrf
                        <input type="hidden" id="password-id" name="id">

                        <div class="modal-body">
                            <div class="row g-3">
                                {{-- Nombre --}}
                                <div class="col-12 col-md-12">
                                    <label for="nombre_completo" class="form-label">
                                        Nombre
                                    </label>

                                    <input type="text" id="nombre_completo" name="nombre_completo" class="form-control"
                                        placeholder="Ingrese el nombre" aria-describedby="error-nombre-completo" disabled>
                                </div>

                                {{-- Contraseña --}}
                                <div class="col-12 col-md-6">
                                    <label for="pw_nuevo" class="form-label">
                                        Contraseña
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="password" id="pw_nuevo" name="password"
                                        class="form-control" aria-describedby="error-password">

                                    <div id="error-pw-nuevo" class="invalid-feedback" data-error-for="password"></div>
                                </div>

                                {{-- Confirmar Contraseña --}}
                                <div class="col-12 col-md-6">
                                    <label for="pw_nuevo_confirmation" class="form-label">
                                        Confirmar Contraseña
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="password" id="pw_nuevo_confirmation" name="password_confirmation"
                                        class="form-control" aria-describedby="error-password-confirmation">

                                    <div id="error-pw-nuevo-confirmation" class="invalid-feedback" data-error-for="password_confirmation"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Pie del modal --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button type="submit" class="btn btn-primary" id="btnActualizarPassword">
                                <i class="bi bi-check-lg me-1"></i> Actualizar contraseña
                            </button>
                        </div>
                    </form>
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
    @vite('resources/js/filtros-datatables.js')
    {{-- Inicialización de DataTables --}}
    <script>

        let modoFormulario = 'crear';

        const filtrosTabla = [
            { id: '#filtro_rol', nombre: 'Rol' }
        ];

        document.addEventListener('DOMContentLoaded', function () {
            window.actualizarBadges(filtrosTabla);
        });

        function mostrarUsuario(usuario) {
            window.limpiarErrores('#form-usuario');
            $('#labelForm').html('Editar');

            $('#edit-id').val(usuario.id);
            $('#nombre').val(usuario.nombre);
            $('#primer_apellido').val(usuario.primer_apellido);
            $('#segundo_apellido').val(usuario.segundo_apellido);
            $('#curp').val(usuario.curp);
            $('#celular').val(usuario.celular);
            $('#email').val(usuario.email);
            window.mostrarSelect2('#role_id', usuario.roles[0].id, usuario.roles[0].name);
        }

        $(document).ready(function () {

            $('#btn_limpiar_filtros').on('click', function () {
                window.limpiarFiltros(filtrosTabla, table);
            });

            $('#filtro_rol').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Selecciona el rol',
                allowClear: true,
                ajax: {
                    url: "{{ route('filtros.roles') }}",
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
                            results: data.roles,
                            pagination: {
                                more: data.more
                            }
                        };
                    }
                }
            });

            var table = $('#tabla-usuario').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('datatable.usuarios') }}",
                    type: "POST",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (d) {
                        d.rol  = $('#filtro_rol').val();
                    }
                },
                columns: [
                    { data: "id", name: "usuario.id", visible: false, searchable: false },
                    { data: "nombreCompleto", name: "nombreCompleto" },
                    { data: "celular", name: "usuario.celular" },
                    { data: "email", name: "usuario.email" },
                    { data: "rolNombre", name: "roles.name" },
                    { data: "estatus", name: "usuario.estatus",
                        render: function (data, type, row) {
                            if (data == "1") {
                                return 'Activada';
                            }
                            if (data == "0") {
                                return 'Desactivada';
                            }
                        }
                    },
                    {
                        data: null, name: 'acciones', orderable: false,  searchable: false,
                        render: function (data, type, row) {
                            if (type !== 'display') {
                                return '';
                            }

                            let botones = '';
                            @can('usuarios.editar')
                                botones += `
                                    <button class="btn btn-sm btn-primary password-btn" data-id="${data.id}"
                                        title="Cambiar contraseña" aria-label="Cambiar contraseña">
                                        <i class="bi bi-key-fill"></i>
                                    </button>
                                    `;
                            @endcan
                            @can('usuarios.editar')
                                botones += `
                                    <button class="btn btn-sm btn-success edit-btn" data-id="${data.id}"
                                        title="Editar usuario" aria-label="Editar usuario">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    `;
                            @endcan
                            @can('usuarios.eliminar')
                                botones += `
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}"
                                            title="Eliminar usuario" aria-label="Eliminar usuario">
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
                    { responsivePriority: 2, targets: 1 },
                    { responsivePriority: 3, targets: -3 },
                    { responsivePriority: 4, targets: -4 },
                    { "width": "15%", "targets": 6 }
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
                    [4, 'asc'],
                    [1, 'asc'],
                ],
            });

            $('.filtro').on('change', function () {
                table.ajax.reload();
                window.actualizarBadges(filtrosTabla);
            });

            //Función para llenar formulario de edición
            $(document).on('click', '.edit-btn', function () {
                modoFormulario = 'editar';
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('usuarios.buscar') }}',
                    method: 'post',
                    data: {
                        id: id
                    },
                    cache: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 200) {

                            let usuario = response.usuario;
                            mostrarUsuario(usuario);
                            window.limpiarErrores('#form-usuario');
                            $('#formUsuarioModal').modal('show');
                        }
                        if(response.status == 404){
                            Swal.fire({
                                title: "Error!",
                                text: "Usuario no encontrada!",
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr) {
                        const $form = $('#form-usuario');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        window.manejarErrorAjax(xhr, {
                            mensajes: {
                                404: 'El usuario que intentas consultar no existe.'
                            },
                            log: 'Error al consultar el usuario'
                        });
                    }
                });
            });

            //Función para llenar formulario de cambio de contraseña
            $(document).on('click', '.password-btn', function () {
                modoFormulario = 'editar';
                const $form = $(this);
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('usuarios.buscar') }}',
                    method: 'post',
                    data: {
                        id: id
                    },
                    cache: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            let usuario = response.usuario;
                            $('#password-id').val(usuario.id);
                            $('#nombre_completo').val(usuario.nombre_completo);
                            $('#formPasswordModal').modal('show');
                        }
                    },
                    error: function (xhr) {
                        const $form = $('#form-usuario');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        window.manejarErrorAjax(xhr, {
                            mensajes: {
                                404: 'El usuario que intentas consultar no existe.'
                            },
                            log: 'Error al consultar el usuario'
                        });
                    }
                });
            });
        });

        //Función para agregar y actualizar usuarios
        $('#form-usuario').submit(function (e) {
            e.preventDefault();
            const id = $('#edit-id').val();
            const esEdicion = id > 0;

            window.enviarFormulario({
                $form: $(this),
                url: esEdicion ? '{{ url('usuarios') }}/' + id : '{{ route('usuarios.crear') }}',
                metodo: esEdicion ? 'PUT' : 'POST',
                modal: '#formUsuarioModal',
                tabla: '#tabla-usuario',
                textoExito: esEdicion
                    ? 'El registro ha sido actualizado correctamente.'
                    : 'El registro ha sido agregado correctamente.',
                mensajes: { 404: 'Usuario no encontrado!' }
            });
        });

        $('#form-password').submit(function (e) {
            e.preventDefault();

            window.enviarFormulario({
                $form: $(this),
                url: '{{ url('usuarios') }}/' + $('#password-id').val() + '/password',
                metodo: 'PUT',
                modal: '#formPasswordModal',
                tabla: '#tabla-usuario',
                textoExito: 'La contraseña ha sido actualizada correctamente.',
                mensajes: { 404: 'Usuario no encontrado!' }
            });
        });

        $(document).on('click', '.delete-btn', function () {
            const id = $(this).data('id');

            window.eliminarRegistro({
                url: '{{ route('usuarios.eliminar', ':id') }}'.replace(':id', id),
                tabla: '#tabla-usuario',
                textoBoton: 'Sí, eliminar usuario!',
                textoExito: 'El usuario ha sido eliminado.',
                mensajes: { 404: 'El usuario ya no existe.' }
            });
        });
    </script>
@endpush
