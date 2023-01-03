@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- Vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/buttons.bootstrap5.min.css') }}">
@endsection
@section('page-style')
    <!-- Page css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/base/plugins/forms/form-validation.css') }}">
    <style>
        .dataTables-Header {
            margin: 2rem 0;
        }

        .btn-new {
            border-radius: 0 !important;
        }

        .search-content {
            width: 100% !important;
        }

        .search-content div {
            width: 100% !important;
        }

        .search-content div .dataTables_filter label {
            width: 100% !important;
            display: flex;
            flex-wrap: wrap;
            justify-content: end;
            padding: 0;
            position: relative;
        }

        .search-content div .dataTables_filter label div {
            width: 5% !important;
            border-radius: 0.25rem !important;
            display: flex;
            justify-content: end;
            align-items: center;
            padding: 0;
            margin: 0;
            border-right: 0;
            position: absolute;
            right: 1rem;
            top: .6rem;
            padding-top: .50rem;
            padding-bottom: .50rem;
        }

        .search-content div .dataTables_filter label div svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .search-content div .dataTables_filter label input {
            width: 100% !important;
            border-radius: 0.25rem !important;
            margin: 0;
            padding-top: 1rem;
            padding-bottom: 1rem;
            border-color: #4b4b4b !important;
        }

        .author select,
        .category select,
        .status select,
        .language select {
            border-radius: 0 !important;
        }

        .author,
        .category,
        .status {
            margin-right: 16px;
        }

        .paginate_button.page-item.previous {
            margin-right: .4rem !important;
        }

        .paginate_button.page-item.previous a {
            border-radius: 5rem !important;
            width: 1rem;
        }


        .dataTables_paginate.paging_simple_numbers ul li:nth-last-child(2) a {
            border-radius: 0 5rem 5rem 0;
        }

        .dataTables_paginate.paging_simple_numbers ul li:nth-child(2) a {
            border-radius: 5rem 0 0 5rem;
        }

        .paginate_button.page-item.next {
            margin-left: .4rem !important;
            width: 1rem;
        }

        .paginate_button.page-item.next a {
            border-radius: 5rem !important;
        }

        .dataTables_paginate.paging_simple_numbers {
            padding-right: 3rem;
        }

    </style>
@endsection

@section('title', __('locale.tags'))

@section('content')

    <!-- table -->
    <div class="card">
        <div class="d-flex justify-content-between align-items-center header-actions mx-2 row px-xxl-75 dataTables-Header">

            <div class="col-sm-12 col-md-6">
                <h3 class="link-dark">{{ __('locale.tags') }}</h3>
            </div>
            <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                <a href="{{ route('tags.create') }}"
                    class="btn btn-flat-primary mb-1 btn-new">{{ __('locale.new-tag') }} <i data-feather='plus'></i></a>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center header-actions row px-xxl-1 link-dark overflow-hidden">
            <hr class="link-dark" />
        </div>
        <input type="hidden" name="getJson" id="getJson" value="{{ route('tags.get-json') }}" />
        <input type="hidden" name="urlBase" id="urlBase" value="{{ route('tags.index') }}">
        <div class="table-responsive">
            <table class="user-list-table table">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="col-6">
                            <div class="ps-md-4">{{ __('locale.name') }}</div>
                        </th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- table -->
    <div class="vertical-modal-ex">
        <!-- Modal -->
        <div class="modal fade" id="deleteUserModel" tabindex="-1" aria-labelledby="deleteUserModelTitle"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius: 0!important;">
                    <div class="modal-body pb-md-4">
                        <div class="text-center pt-md-4 pb-md-3">
                            <h2>{{ __('locale.do-you-delete-tag') }}</h2>
                            <h4>{{ __('locale.irreversible-action') }}</h4>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-outline-primary" style="border-radius: 0!important;"
                                data-bs-dismiss="modal">{{ __('locale.cancel') }}</button>

                            <button type="button" class="btn btn-danger ms-2" style="border-radius: 0!important;"
                                onclick="confirmDelete()" data-bs-dismiss="modal">{{ __('locale.delete') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form method="POST" action="" class="d-flex" id="form-delete">
        @csrf
        @method( 'DELETE' )
        <input type="hidden" name="urlDelete" id="urlDelete" value="{{ route('tags.destroy', [-1]) }}">
    </form>
    <!-- Vertical modal end-->
@endsection

@section('vendor-script')
    <!-- Vendor js files -->
    <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}"></script>
@endsection
@section('page-script')
    <script>
        $(function() {
            ('use strict');
            var dtUserTable = $('.user-list-table'),
                assetPath = '../../../app-assets/',
                userView = 'app-user-view-account.html',
                statusObj = {
                    Publicada: {
                        title: 'Publicada',
                        class: 'badge-light-success'
                    },
                    Borrador: {
                        title: 'Borrador',
                        class: 'badge-light-warning'
                    },
                    Archivada: {
                        title: 'Archivada',
                        class: 'badge-light-danger'
                    }
                };
            languageObj = {
                Español: {
                    title: 'Español',
                    class: 'flag-icon flag-icon-es mr-50'
                },
                Inglés: {
                    title: 'Inglés',
                    class: 'flag-icon flag-icon-gb mr-50'
                },
            };

            if ($('body').attr('data-framework') === 'laravel') {
                assetPath = $('body').attr('data-asset-path');
                userView = assetPath + 'app/user/view/account';
            }

            let url = $('#getJson').val()
            //console.log( url );
            // Users List datatable
            if (dtUserTable.length) {
                dtUserTable.DataTable({
                    ajax: `${url}`, // JSON file to add data
                    columns: [
                        // columns according to JSON
                        {
                            data: 'order'
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'name'
                        },
                        {
                            data: ''
                        },
                    ],
                    order: [
                        [0, "asc"]
                    ],
                    columnDefs: [
                        {
                            // For Responsive
                            className: 'control',
                            orderable: false,
                            responsivePriority: 2,
                            targets: 0,
                            render: function(data, type, full, meta) {
                                return '';
                            }
                        },
                        {
                            targets: 1,
                            render: function(data, type, full, meta) {
                                data = full['order']
                                return (`<div class="avatar bg-light-primary">
                                        <span class="avatar-content">${data}</span>
                                    </div>`);
                            }
                        },
                        {
                            targets: 2,
                            render: function(data, type, full, meta) {
                                return data;
                            }
                        },
                        {
                            // Actions
                            targets: -1,
                            title: '',
                            orderable: false,
                            responsivePriority: 4,
                            render: function(data, type, full, meta) {
                                //console.log('Form actions');
                                //console.log(full);
                                let urlBase = $('#urlBase').val();
                                return (
                                    '<div class="d-flex align-items-center col-actions">' +
                                    '<div class="dropdown">' +
                                    '<a class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                                    feather.icons['more-vertical'].toSvg({
                                        class: 'font-medium-2 text-body'
                                    }) +
                                    '</a>' +
                                    '<div class="dropdown-menu dropdown-menu-end">' +
                                    '<a href="' + urlBase + '/' + full.id +
                                    '/edit" class="dropdown-item">' +
                                    feather.icons['edit'].toSvg({
                                        class: 'font-small-4 me-50'
                                    }) +
                                    'Editar tag</a>' +
                                    `<button type="button" class="dropdown-item text-danger" data-id="${full.id}" id="btn-${full.id}" data-bs-toggle="modal" data-bs-target="#deleteUserModel" onclick="deleteUser(this)">
                                    ${feather.icons['trash'].toSvg({ class: 'font-small-4 me-50' })} Borrar tag</button>` +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            }
                        }
                    ],
                    dom: '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-50 mb-1"' +
                        '<"col-sm-12 col-md-12 col-lg-12 ps-xl-50 ps-0 pe-25"<"dt-action-buttons d-flex align-items-center justify-content-md-between justify-content-between flex-sm-nowrap flex-wrap search-content"<"mx-0"f rounded-2>>>' +
                        '>t' +
                        '<"d-flex justify-content-between mx-2 row"' +
                        '<"col-sm-12 col-md-6"i>' +
                        '<"col-sm-12 col-md-6"p>' +
                        '>',
                    language: {
                        sLengthMenu: 'Show _MENU_',
                        search: "",
                        searchPlaceholder: ''
                    },
                    oLanguage: {
                        sSearch: '<div class="link-primary"><i data-feather="search"></i></div>',
                        sSearchPlaceholder: 'Buscar...'
                    },
                    // For responsive popup
                    responsive: {
                        details: {
                            display: $.fn.dataTable.Responsive.display.modal({
                                header: function(row) {
                                    var data = row.data();
                                    return 'Details of ' + data['author'];
                                }
                            }),
                            type: 'column',
                            renderer: function(api, rowIdx, columns) {
                                var data = $.map(columns, function(col, i) {
                                    return col.title !==
                                        '' // ? Do not show row in modal popup if title is blank (for check box)
                                        ?
                                        '<tr data-dt-row="' +
                                        col.rowIdx +
                                        '" data-dt-column="' +
                                        col.columnIndex +
                                        '">' +
                                        '<td>' +
                                        col.title +
                                        ':' +
                                        '</td> ' +
                                        '<td>' +
                                        col.data +
                                        '</td>' +
                                        '</tr>' :
                                        '';
                                }).join('');

                                return data ? $('<table class="table"/>').append('<tbody>' + data +
                                    '</tbody>') : false;
                            }
                        }
                    },
                    language: {
                        paginate: {
                            // remove previous & next text from pagination
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        }
                    },
                    initComplete: function() {
                        // Adding role filter once table initialized
                    }
                });
            }
        });

        $('#form-delete').attr('action', '');

        const deleteUser = (element) => {
            let id = element.id;
            let themeId = $('#' + id).attr('data-id');
            let urlDelete = $('#urlDelete').val();
            urlDelete = urlDelete.replace('-1', themeId);
            $('#form-delete').attr('action', urlDelete);
        }

        const confirmDelete = () => {
            $('#form-delete').submit();
        }
    </script>
    @if ($message = Session::get('message'))
        <script>
            var isRtl = $('html').attr('data-textdirection') === 'rtl';
            setTimeout(function() {
                toastr['success'](
                    'Success',
                    "{{ $message }}", {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: isRtl
                    }
                );
            }, 500);
        </script>
    @endif
@endsection
