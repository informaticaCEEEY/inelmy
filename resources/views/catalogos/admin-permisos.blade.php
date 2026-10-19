
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

@section('title', 'Catálogo de permisos')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-4">

    {{-- Encabezado --}}
    <div class="d-flex flex-wrap justify-content-between
                align-items-center gap-3 mb-4">

        <div>
            <h1 class="h3 mb-1">Catálogo de permisos</h1>
            <p class="text-muted mb-0">
                Administración de permisos.
            </p>
        </div>
        @can('permisos.administrar')
            <button class="btn btn-inelmy" onclick="window.agregarRegistro('#form-permiso', '#formPermisoModal')">
                <i class="bi bi-person-plus me-1"></i>
                Nuevo permiso
            </button>
        @endcan
    </div>
    {{-- Tabla de permisos --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="permisos-table-wrapper">
                <table id="tabla-permiso" class="display">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
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
        {{-- Modal para agregar y editar permisos --}}
        <div class="modal fade modal-inelmy" id="formPermisoModal" tabindex="-1"
            aria-labelledby="formPermisoModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

                <div class="modal-content">

                    {{-- Encabezado --}}
                    <div class="modal-header">
                        <h5 class="modal-title" id="formPermisoModalLabel">
                            <i class="bi bi-person-plus me-2"></i>
                            <span id="labelForm">Agregar</span> permiso
                        </h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    {{-- Formulario --}}
                    <form id="form-permiso" method="POST">
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
                            </div>
                        </div>

                        {{-- Pie del modal --}}
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Cancelar
                            </button>

                            <button type="submit" class="btn btn-primary" id="btnGuardarPermiso">
                                <i class="bi bi-check-lg me-1"></i> Guardar permiso
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
    {{-- Inicialización de DataTables --}}
    <script>

        let modoFormulario = 'crear';

        function mostrarPermiso(permiso) {
            window.limpiarErrores('#form-permiso');
            $('#labelForm').html('Editar');

            $('#edit-id').val(permiso.id);
            $('#nombre').val(permiso.nombre);
        }

        $(document).ready(function () {

            var table = $('#tabla-permiso').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                responsive: true,
                ajax: {
                    url: "{{ route('datatable.permisos') }}",
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
                        data: null, name: 'acciones', orderable: false,  searchable: false,
                        render: function (data, type, row) {
                            if (type !== 'display') {
                                return '';
                            }

                            let botones = '';
                            @can('permisos.administrar')
                                botones += `
                                    <button class="btn btn-sm btn-success edit-btn" data-id="${data.id}"
                                        title="Editar permiso" aria-label="Editar permiso">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    `;
                                botones += `
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}"
                                            title="Eliminar permiso" aria-label="Eliminar permiso">
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

            //Función para llenar formulario de edición
            $(document).on('click', '.edit-btn', function () {
                modoFormulario = 'editar';
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('permiso.buscar') }}',
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

                            let permiso = response.permiso;
                            mostrarPermiso(permiso);
                            window.limpiarErrores('#form-permiso');
                            $('#formPermisoModal').modal('show');
                        }
                        if(response.status == 404){
                            Swal.fire({
                                title: "Error!",
                                text: "Permiso no encontrada!",
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr) {
                        const $form = $('#form-permiso');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        // Errores de validación de Laravel.
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {

                            const errores = xhr.responseJSON.errors;

                            $.each(errores, function (campo, mensajes) {

                                // Encontrar el input por su atributo name.
                                const $input = $form.find(':input').filter(function () {
                                    return this.name === campo;
                                });

                                // Encontrar el contenedor de error correspondiente.
                                const $contenedor = $form.find('[data-error-for]').filter(function () {
                                    return $(this).attr('data-error-for') === campo;
                                });

                                $input.addClass('is-invalid');

                                $contenedor.text(mensajes[0] ?? 'El valor ingresado no es válido.');
                            });

                            // Enfocar el primer campo inválido.
                            $form.find('.is-invalid').first().trigger('focus');

                            return;
                        }

                        // Errores distintos de validación.
                        console.error('Error al guardar permiso:', xhr.responseJSON ?? xhr.responseText);

                        alert('Ocurrió un error al guardar el permiso. Intenta nuevamente.');
                    }
                });
            });

            //Función para llenar formulario de cambio de contraseña
            $(document).on('click', '.password-btn', function () {
                modoFormulario = 'editar';
                var id = $(this).data('id');
                $.ajax({
                    url: '{{ route('permiso.buscar') }}',
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
                            let permiso = response.permiso;
                            $('#password-id').val(permiso.id);
                            $('#nombre_completo').val(permiso.nombre_completo);
                            $('#formPasswordModal').modal('show');
                        }
                        if(response.status == 404){
                            Swal.fire({
                                title: "Error!",
                                text: "Permiso no encontrado!",
                                icon: "error"
                            });
                        }
                    },
                    error: function (xhr) {
                        const $form = $('#form-permiso');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        // Errores de validación de Laravel.
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {

                            const errores = xhr.responseJSON.errors;

                            $.each(errores, function (campo, mensajes) {

                                // Encontrar el input por su atributo name.
                                const $input = $form.find(':input').filter(function () {
                                    return this.name === campo;
                                });

                                // Encontrar el contenedor de error correspondiente.
                                const $contenedor = $form.find('[data-error-for]').filter(function () {
                                    return $(this).attr('data-error-for') === campo;
                                });

                                $input.addClass('is-invalid');
                                $contenedor.text(mensajes[0] ?? 'El valor ingresado no es válido.');
                            });

                            // Enfocar el primer campo inválido.
                            $form.find('.is-invalid').first().trigger('focus');
                            return;
                        }

                        // Errores distintos de validación.
                        console.error('Error al guardar permiso:', xhr.responseJSON ?? xhr.responseText);
                        alert('Ocurrió un error al guardar el permiso. Intenta nuevamente.');
                    }
                });
            });
        });

        //Función para actualizar permisos
        $('#form-permiso').submit(function (e) {
            e.preventDefault();
            var id =  $('#edit-id').val();
            let usuarioData = new FormData(this);

            if(id > 0){
                usuarioData.append('_method', 'PUT');
                $.ajax({
                    url: '{{ url('permiso') }}/' + id,
                    method: 'POST',
                    data: usuarioData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire({
                                title: "Éxito!",
                                text: "El registro ha sido actualizado correctamente.",
                                icon: "success"
                            });
                            $('#form-permiso')[0].reset();

                            window.limpiarErrores('#form-permiso');

                            $('#formPermisoModal').modal('hide');
                            $('#tabla-permiso').DataTable().ajax.reload();
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Permiso no encontrado!",
                                icon: "error"
                            });
                            $('#form-permiso')[0].reset();
                            $('#formPermisoModal').modal('hide');
                            $('#tabla-permiso').DataTable().ajax.reload();
                        }
                    },
                    error: function (xhr) {
                        const $form = $('#form-permiso');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        // Errores de validación de Laravel.
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {

                            const errores = xhr.responseJSON.errors;

                            $.each(errores, function (campo, mensajes) {

                                // Encontrar el input por su atributo name.
                                const $input = $form.find(':input').filter(function () {
                                    return this.name === campo;
                                });

                                // Encontrar el contenedor de error correspondiente.
                                const $contenedor = $form.find('[data-error-for]').filter(function () {
                                    return $(this).attr('data-error-for') === campo;
                                });

                                $input.addClass('is-invalid');
                                $contenedor.text(mensajes[0] ?? 'El valor ingresado no es válido.');
                            });

                            // Enfocar el primer campo inválido.
                            $form.find('.is-invalid').first().trigger('focus');
                            return;
                        }

                        // Errores distintos de validación.
                        console.error('Error al guardar permiso:', xhr.responseJSON ?? xhr.responseText);
                        alert('Ocurrió un error al guardar el permiso. Intenta nuevamente.');
                    }
                });
            }else{
                $.ajax({
                    url: '{{ route('permiso.crear') }}',
                    method: 'post',
                    data: usuarioData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status == 200) {
                            //alert("Saved successfully");
                            Swal.fire({
                                title: "Éxito!",
                                text: "El registro ha sido agregado correctamente.",
                                icon: "success"
                            });
                            $('#form-permiso')[0].reset();

                            window.limpiarErrores('#form-permiso');

                            $('#formPermisoModal').modal('hide');
                            $('#tabla-permiso').DataTable().ajax.reload();
                        }
                    },
                    error: function (xhr) {
                        const $form = $('#form-permiso');

                        // Eliminar mensajes anteriores.
                        window.limpiarErrores($form);

                        // Errores de validación de Laravel.
                        if (xhr.status === 422 && xhr.responseJSON?.errors) {

                            const errores = xhr.responseJSON.errors;

                            $.each(errores, function (campo, mensajes) {

                                // Encontrar el input por su atributo name.
                                const $input = $form.find(':input').filter(function () {
                                    return this.name === campo;
                                });

                                // Encontrar el contenedor de error correspondiente.
                                const $contenedor = $form.find('[data-error-for]').filter(function () {
                                    return $(this).attr('data-error-for') === campo;
                                });

                                $input.addClass('is-invalid');
                                $contenedor.text(mensajes[0] ?? 'El valor ingresado no es válido.');
                            });

                            // Enfocar el primer campo inválido.
                            $form.find('.is-invalid').first().trigger('focus');
                            return;
                        }

                        // Errores distintos de validación.
                        console.error('Error al guardar permiso:', xhr.responseJSON ?? xhr.responseText);
                        alert('Ocurrió un error al guardar el permiso. Intenta nuevamente.');
                    }
                });
            }
        });

        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no se puede revertir!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, eliminar permiso!",
                cancelButtonText: "No, cancelar!",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('permiso.eliminar') }}',
                        type: 'DELETE',
                        data: {id: id},
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            //console.log(response); // Debugging: log the response
                            if (response.status === 200) {
                                Swal.fire({
                                    title: "Eliminado!",
                                    text: "El permiso ha sido eliminado.",
                                    icon: "success"
                                });
                                $('#tabla-permiso').DataTable().ajax.reload(); // Reload the table data
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "No se pudo eliminar el permiso.",
                                    icon: "error"
                                });
                            }
                        }
                    });
                }
            })
        });
    </script>
@endpush
