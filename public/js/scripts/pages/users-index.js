$(function () {
    ('use strict');
    var dtUserTable = $('.user-list-table'),
        assetPath = '../../../app-assets/',
        userView = 'app-user-view-account.html',
        statusObj = {
            Publicada: { title: 'Publicada', class: 'badge-light-success' },
            Borrador: { title: 'Borrador', class: 'badge-light-warning' },
            Archivada: { title: 'Archivada', class: 'badge-light-danger' }
        };
        languageObj = {
            Español: { title: 'Español', class: 'flag-icon flag-icon-es mr-50' },
            Inglés: { title: 'Inglés', class: 'flag-icon flag-icon-gb mr-50' },
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
                { data: '' },
                { data: 'id' },
                { data: 'user' },
                { data: 'email' },
                { data: 'newsletter' },
                { data: 'role' },
                { data: 'createdAt' },
                { data: '' },
            ],
            order: [[ 1, "asc" ]],
            columnDefs: [
                {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    // For Responsive
                    className: 'control',
                    orderable: false,
                    responsivePriority: 2,
                    targets: 1,
                    render: function (data, type, full, meta) {
                        return '';
                    }
                },
                {
                    // For Responsive
                    orderable: false,
                    responsivePriority: 2,
                    targets: 2,
                    render: function (data, type, full, meta) {
                        return (`<div class="ps-md-4">${data}</div>`);
                    }
                },
                {
                    // For Responsive
                    orderable: false,
                    responsivePriority: 2,
                    targets: 4,
                    render: function (data, type, full, meta) {
                        if ( data == 1 ) {
                            return ('Sí');
                        } else {
                            return ('');
                        }
                    }
                },
                {
                    // Actions
                    targets: -1,
                    title: '',
                    orderable: false,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {
                        //console.log('Form actions');
                        //console.log(full);
                        let urlBase = $('#urlBase').val();
                        return (
                            '<div class="d-flex align-items-center col-actions">' +
                            '<div class="dropdown">' +
                            '<a class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown">' +
                            feather.icons['more-vertical'].toSvg({ class: 'font-medium-2 text-body' }) +
                            '</a>' +
                            '<div class="dropdown-menu dropdown-menu-end">' +
                            '<a href="' + urlBase + '/' + full.id + '/edit" class="dropdown-item">' +
                            feather.icons['edit'].toSvg({ class: 'font-small-4 me-50' }) +
                            'Editar usuario</a>' +
                            `<button type="button" class="dropdown-item text-danger" data-id="${full.id}" id="btn-${full.id}" data-bs-toggle="modal" data-bs-target="#deleteUserModel" onclick="deleteUser(this)">
                            ${feather.icons['trash'].toSvg({ class: 'font-small-4 me-50' })} Borrar usuario</button>`+
                            '</div>' +
                            '</div>' +
                            '</div>'
                        );
                    }
                }
            ],
            dom:
                '<"d-flex justify-content-between align-items-center header-actions mx-2 row mt-50 mb-1"' +
                '<"col-sm-12 col-md-9 col-lg-9 ps-xl-50 ps-0 pe-25"<"dt-action-buttons d-flex align-items-center justify-content-md-between justify-content-between flex-sm-nowrap flex-wrap search-content"<"me-1"f rounded-2>>>' +
                `<"col-sm-12 col-md-3 col-lg-3 ps-0 d-flex"<"dt-action-buttons d-flex align-items-center justify-content-md-start justify-content-center flex-sm-nowrap flex-wrap"><"role mt-50 me-1 col-md-8 col-12 rounded-2"><"search me-1 mt-50 col-md-1 col-12">>` +
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
                sSearchPlaceholder: 'Buscar por nombre o correo electrónico...'
            },
            // For responsive popup
            responsive: {
                details: {
                    display: $.fn.dataTable.Responsive.display.modal({
                        header: function (row) {
                            var data = row.data();
                            return 'Details of ' + data['author'];
                        }
                    }),
                    type: 'column',
                    renderer: function (api, rowIdx, columns) {
                        var data = $.map(columns, function (col, i) {
                            return col.title !== '' // ? Do not show row in modal popup if title is blank (for check box)
                                ? '<tr data-dt-row="' +
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
                                '</tr>'
                                : '';
                        }).join('');

                        return data ? $('<table class="table"/>').append('<tbody>' + data + '</tbody>') : false;
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
            initComplete: function () {
                // Adding role filter once table initialized
                this.api()
                    .columns(5)
                    .every(function () {
                        var column = this;
                        var select = $(
                            '<select id="role" class="form-select text-capitalize rounded-25 py-md-1 border-dark"><option value=""> Tipo de usuario </option></select>'
                        )
                            .appendTo('.role')
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function (d, j) {
                                select.append('<option value="' + d + '" class="text-capitalize">' + d + '</option>');
                            });
                    });

                this.api()
                    .columns(5)
                    .every(function () {
                        var column = this;
                        var select = $(`<button type="button" class="btn btn-primary rounded-pill py-md-1"><span class="text-dark">Buscar</span></button>`)
                            .appendTo('.search')
                            .on('click', function () {
                                var val = $.fn.dataTable.util.escapeRegex($('.dataTables_filter > label > input[type="search"]').val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                    });
            }
        });
    }
});

const deleteUser = (element) => {
    let id = element.id;
    let userId = $( '#' + id ).attr( 'data-id' );
    console.log(userId);
    let urlDelete = $( '#urlDelete' ).val();
    urlDelete = urlDelete.replace( '-1', userId );
    $( '#form-delete' ).attr( 'action', urlDelete );
}

const confirmDelete = () => {
    $( '#form-delete' ).submit();
}
