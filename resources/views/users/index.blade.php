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
    <link rel="stylesheet" href="{{ asset('css/users-index.css') }}">
@endsection

@section('title', __('locale.users'))

@section('content')

    <!-- table -->
    <div class="card">
        <div class="d-flex justify-content-between align-items-center header-actions mx-2 row px-xxl-75 dataTables-Header">

            <div class="col-sm-12 col-md-6">
                <h3 class="link-dark">{{ __('locale.users') }}</h3>
            </div>
            <div class="col-sm-12 col-md-6 d-flex justify-content-end">
                <a href="{{ route('users.create') }}" class="btn btn-flat-primary mb-1 btn-new">{{ __('locale.new-user') }} <i data-feather='plus'></i></a>
            </div>
        </div>
        <div class="d-flex justify-content-center align-items-center header-actions row px-xxl-1 link-dark overflow-hidden">
            <hr class="link-dark"/>
        </div>
        <input type="hidden" name="getJson" id="getJson" value="{{ route('users.get-json') }}" />
        <input type="hidden" name="urlBase" id="urlBase" value="{{ route('users.index') }}">
        <div class="table-responsive">
            <table class="user-list-table table">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th></th>
                        <th class="ps-md-4">
                            <div class="ps-md-2">{{ __('locale.user') }}</div>
                        </th>
                        <th class="">
                            <div class="">{{ __('locale.email') }}</div>
                        </th>
                        <th class="">
                            <div class="">{{ __('locale.newsletter') }}</div>
                        </th>
                        <th class="">
                            <div class="">{{ __('locale.role') }}</div>
                        </th>
                        <th>{{ __('locale.created-at') }}</th>
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
                            <h2>{{ __('locale.do-you-delete-user') }}</h2>
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
    <form method="POST"
        action=""
        class="d-flex" id="form-delete">
        @csrf
        @method( 'DELETE' )
        <input type="hidden" name="urlDelete" id="urlDelete" value="{{ route('users.destroy', [-1]) }}">
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
    <script src="{{ asset('js/scripts/pages/users-index.js') }}"></script>
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
