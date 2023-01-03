@extends('layouts/contentLayoutMaster')

@section('title', 'Usuarios')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('vendors/css/animate/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/sweetalert2.min.css') }}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{ asset('css/base/plugins/extensions/ext-component-sweet-alerts.css') }}">
@endsection

@section('content')
    <!-- Responsive tables start -->
    <div class="row" id="table-responsive">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-bottom">
                    <div class="head-label">
                        <h4 class="mb-0">Usuarios</h4>
                    </div>
                    <div class="dt-action-buttons text-end">

                        <div class="dt-buttons d-inline-flex">
                            <button id="addUsers" class="dt-button create-new btn btn-primary" tabindex="0"
                                aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal"
                                data-bs-target="#createUserModal">
                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-plus me-50 font-small-4">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Nuevo usuario
                                </span>
                            </button>
                        </div>

                    </div>
                </div>
                <!--Search Form -->
                <div class="card-body mt-2">
                    <form id="formSearchUsers" class="dt_adv_search">
                        <div class="row g-1 mb-md-1">
                            <div class="col-md-6">

                                <div class="input-group input-group-merge mb-2">
                                    <span class="input-group-text" id="basic-addon-search"><i
                                            data-feather="search"></i></span>
                                    <input id="searchCriteriaUsers" name="searchCriteriaUsers" type="text"
                                        class="form-control" placeholder="Buscar usuario" aria-label="Search..."
                                        aria-describedby="basic-addon-search" />
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <hr class="my-0" />
                <div class="">
                    <div class="card contentTableUsers">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Correo Electrónico</th>
                                    <th>Fecha de Registro</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td style="display: none;">{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ date('d/m/Y', strtotime($user->created_at)) }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0"
                                                    data-bs-toggle="dropdown">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-more-vertical">
                                                        <circle cx="12" cy="12" r="1"></circle>
                                                        <circle cx="12" cy="5" r="1"></circle>
                                                        <circle cx="12" cy="19" r="1"></circle>
                                                    </svg>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a value="{{ $user->id }}" id="editUsers" class="dropdown-item"
                                                        href="#" data-bs-toggle="modal" data-bs-target="#editUserModal"
                                                        OnClick='editUsers({{ $user->id }});'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-edit-2 me-50">
                                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                            </path>
                                                        </svg>
                                                        <span>Editar</span>
                                                    </a>
                                                    <a value="{{ $user->id }}" id="deteleUsers" class="dropdown-item"
                                                        href="#" data-bs-toggle="modal" data-bs-target="#deteleUserModal"
                                                        OnClick='deleteUsers({{ $user->id }});'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-trash me-50">
                                                            <polyline points="3 6 5 6 21 6"></polyline>
                                                            <path
                                                                d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                            </path>
                                                        </svg>
                                                        <span>Eliminar</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr class="my-0" />

                        <div id="paginatorTableUsers" class="d-flex justify-content-between p-2">

                            Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de un total de
                            {{ $users->total() }} registros

                            {{ $users->links() }}

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal crear usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFullTitle">Nuevo usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="mjeDisplayWindow"></div>

                <div class="modal-body">

                    <div class="d-flex justify-content-center my-1">
                        <div class="spinner-border" role="status" aria-hidden="true"></div>&nbsp;<b>Cargando datos, por
                            favor espere...</b>
                    </div>
                    <!-- Aquí va el contenido dinámico que viene de las rutas create/edit -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="createUsers" type="button" class="btn btn-primary">Dar de alta</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin modal crear usuario -->


    <!-- Modal editar usuario -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFullTitle">Editar usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mjeDisplayWindow"></div>
                    <div class="d-flex justify-content-center my-1">
                        <div class="spinner-border" role="status" aria-hidden="true"></div>&nbsp;<b>Cargando datos, por
                            favor espere...</b>
                    </div>
                    <!-- Aquí va el contenido dinámico que viene de las rutas create/edit -->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button id="updateUsers" type="button" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Fin modal editar usuario -->


    <!-- Responsive tables end -->
@endsection
<script src="vendors/js/jquery/jquery.js"></script>
<script src="vendors/js/extensions/sweetalert2.all.min.js"></script>
<script>
    /*==================== PAGINATION =========================*/
    $(document).on('click', '#paginatorTableUsers a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        $.ajax({
            beforeSend: function() {
                $('.contentTableUsers').html(
                    '<div class="d-flex justify-content-center my-1"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
                    );
            },
            method: 'GET',
            url: '/public/partialuser?page=' + page,
            data: {
                per_page: 10,
                search: $('#searchCriteriaUsers').val()
            }
        }).done(function(data) {
            console.log(data);
            $('.contentTableUsers').html(data);
        }).fail(function(jqXHR, textStatus) {
            var heightLoading = $('.contentTableUsers').height();
            var alto = heightLoading + 20;
            $('.contentTableUsers').html(
                '<div style="text-align:center; color: #8EABDB; margin: auto 0;height:' + alto +
                'px;margin-top:' + (alto / 2) + 'px;">Error al cargar los datos.</div>');
        });
    });


    jQuery(function($) {
        var $searchCriteriaUsers = $('#searchCriteriaUsers');

        //buscador
        /*==================== SEARCH CRITERIA =========================*/
        $('#searchCriteriaUsers').keyup(function(e) {
            e.preventDefault();
            console.log($(this).val());
            getListUsers();
        });


        //Llamar ventana para crear usuarios
        $("#addUsers").click(function(e) {
            e.preventDefault();
            addUsers();
        });

        //Llamar ventana para crear usuarios
        $("#createUsers").click(function(e) {
            e.preventDefault();
            createUsers();
        });

        //Aquí no se llama el contenido de la ventana de editar porque desde el botón
        //se hace el llamado con un onclick porque se envía ek id del registro como parámetro

        //Llamar ventana para crear usuarios
        $("#updateUsers").click(function(e) {
            e.preventDefault();
            updateUsers();
        });


    });


    var xhr = null;

    function getListUsers() {
        if (xhr != null) {
            xhr.abort();
            xhr = null;
        }
        xhr = $.ajax({
            beforeSend: function() {
                $('.contentTableUsers').html(
                    '<div class="d-flex justify-content-center my-1"><div class="spinner-border" role="status" aria-hidden="true"></div></div>'
                    );
            },
            method: 'GET',
            url: '/public/partialuser?page=1',
            data: {
                per_page: 10,
                search: $('#searchCriteriaUsers').val()
            }
        }).done(function(data) {
            $('.contentTableUsers').html(data);
        }).fail(function(jqXHR, textStatus) {
            var heightLoading = $('.contentTableUsers').height();
            var alto = heightLoading + 20;
            $('.contentTableUsers').html(
                '<div style="text-align:center; color: #8EABDB; margin: auto 0;height:' + alto +
                'px;margin-top:' + (alto / 2) + 'px;">Error al cargar los datos.</div>');
        });
    }

    function reloadTableUsers() {
        $('#formSearchUsers').trigger('reset');
        getListUsers();
    }

    function addUsers() {
        var route = "/users/create";
        $('.mjeDisplayWindow').html('');

        $.get(route, function(form) {
            $('.modal-body').html(form);
        });
    }


    function editUsers(val) {
        console.log(val);
        var route = "/users/" + val + "/edit";
        $('.mjeDisplayWindow').html('');

        $.get(route, function(form) {
            $('.modal-body').html(form);
        });
    }


    function deleteUsers(val) {

        Swal.fire({
            title: 'Está seguro que desea eliminar el usuario?',
            text: "Esta acción no se puede revertir!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, deseo eliminar!',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-outline-danger ms-1'
            },
            buttonsStyling: false
        }).then(function(result) {
            if (result.value) {
                var route = "/users/" + val;

                $.ajax({
                    url: route,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    dataType: 'json',
                    success: function(respuesta) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado!',
                            text: 'El usuario ha sido eliminado.',
                            customClass: {
                                confirmButton: 'btn btn-success'
                            }
                        });
                        reloadTableUsers();
                    },
                    error: function(jqXhr) {
                        var respuesta = jqXhr.responseJSON;
                        console.log(respuesta);
                    }
                });
            }
        });

    }

    //Crear usuarios
    function createUsers() {
        //if ( $('#formCreateUsers').validate().form() ) {

        var formData = new FormData($("form#formCreateUsers")[0]);
        //formData.append( "_token", $('meta[name="csrf-token"]').attr('content') );

        $('.mjeDisplayWindow').append(
            '<div class="d-flex justify-content-center my-1"><div class="spinner-border" role="status" aria-hidden="true"></div>&nbsp;<b>Enviando datos, por favor espere...</b></div>'
            );

        $.ajax({
            url: "/users",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            beforeSend: function() {
                //
            },
            data: formData,
            //async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                console.log(respuesta);
                $('.mjeDisplayWindow').html('');
                $("#createUserModal").modal('toggle');
                reloadTableUsers();
            },
            error: function(jqXhr) {
                var respuesta = jqXhr.responseJSON;
                console.log(respuesta);

                $('.mjeDisplayWindow').html('');

                if (jqXhr.status === 401) {
                    $('.mjeDisplayWindow').append(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><div class="alert-body">No está autorizado</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                }

                if (jqXhr.status === 422) {
                    console.log(jqXhr.status);
                    var errorsHtml = '';
                    $.each(respuesta.errors, function(index, value) {
                        errorsHtml += '<li>' + value + '</li>';
                    });

                    $('.mjeDisplayWindow').append(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><div class="alert-body">' +
                        errorsHtml +
                        '</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                }

                if (jqXhr.status === 500) {
                    $('.mjeDisplayWindow').append(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><div class="alert-body">' +
                        respuesta.message +
                        '</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                }

            }
        });
        //}
    }

    //Editar usuarios
    function updateUsers() {
        //if ( $('#formEditUsers').validate().form() ) {

        var formData = new FormData($("form#formEditUsers")[0]);
        var id = $("#id").val();
        var route = "/users/" + id;
        console.log($("#formEditUsers #name").val());
        $('.mjeDisplayWindow').append(
            '<div class="d-flex justify-content-center my-1"><div class="spinner-border" role="status" aria-hidden="true"></div>&nbsp;<b>Enviando datos, por favor espere...</b></div>'
            );

        $.ajax({
            url: route,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            beforeSend: function() {
                //winEditUsers.enableButtons(false);
            },
            data: formData,
            dataType: "json",
            //async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                $('.mjeDisplayWindow').html('');
                $("#editUserModal").modal('toggle');
                reloadTableUsers();
            },
            error: function(jqXhr) {
                var respuesta = jqXhr.responseJSON;
                console.log(respuesta);

                $('.mjeDisplayWindow').html('');

                if (jqXhr.status === 401) {
                    $('.mjeDisplayWindow').append(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><div class="alert-body">No está autorizado</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                }

                if (jqXhr.status === 422) {
                    console.log(jqXhr.status);
                    var errorsHtml = '';
                    $.each(respuesta.errors, function(index, value) {
                        errorsHtml += '<li>' + value + '</li>';
                    });

                    $('.mjeDisplayWindow').append(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><div class="alert-body">' +
                        errorsHtml +
                        '</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                }

                if (jqXhr.status === 500) {
                    $('.mjeDisplayWindow').append(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert"><div class="alert-body">' +
                        respuesta.message +
                        '</div><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>'
                        );
                }

            }
        });
        //}

    }
</script>
