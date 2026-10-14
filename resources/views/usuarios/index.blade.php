
@extends('layouts.app')

@push('styles')
    @vite('resources/css/navbar.css')
    @vite('resources/css/dashboard.css')
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
    </div>
    {{-- Tabla de usuarios --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
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
    <div class="container">
        <div class="row">
            <div class="modal fade modal-lg" id="formUsuarioModal" tabindex="-1" role="dialog" aria-labelledby="formUsuarioModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <div class="col-11 d-block">
                                <h5 class="modal-title text-center" id="formUsuarioModalLabel"><span id="labelForm">Agregar</span> Usuario</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-usuario" method="post">
                                @csrf
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" form="form-usuario">Guardar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade modal-lg" id="formPasswordModal" tabindex="-1" role="dialog" aria-labelledby="formPasswordModal" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <div class="col-11 d-block">
                                <h5 class="modal-title text-center" id="formPasswordModal">Cambiar Contraseña</h5>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form-password" method="post">
                                @csrf
                            </form>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary" form="form-password">Guardar</button>
                        </div>
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

    {{-- Inicialización de DataTables --}}
    <script>
        $(document).ready(function () {

            console.log('DataTables:', typeof $.fn.DataTable);

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
                            let botones = '';
                            @can('usuarios.editar')
                                botones += `
                                    <button class="btn btn-sm btn-primary password-btn" data-id="${data.id}">Cambiar Contraseña</button>
                                    `;
                            @endcan
                            @can('usuarios.editar')
                                botones += `
                                    <button class="btn btn-sm btn-success edit-btn" data-id="${data.id}">Editar</button>
                                    `;
                            @endcan
                            @can('usuarios.eliminar')
                                botones += `
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="${data.id}">Eliminar</button>
                                    `;
                            @endcan
                            return botones;
                        }
                    }
                ],
                columnDefs: [
                    { responsivePriority: 1, targets: -1 },
                    { responsivePriority: 2, targets: 1 },
                    { responsivePriority: 3, targets: -3 },
                    { responsivePriority: 4, targets: -4 },
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
        });
    </script>
@endpush
