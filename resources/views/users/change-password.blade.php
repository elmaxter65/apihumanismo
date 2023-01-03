@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
        rel="stylesheet">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset('vendors/css/extensions/toastr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/users-create.css') }}">
@endsection

@section('title', __('locale.change-password'))

@section('content')
    <section>
        <div class="row">
            <div class="col-sm-12 col-md-12 mb-1 ps-md-2 pe-md-50">
                <div class="row d-flex">

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-md-12">
                <div class="card px-50">
                    <div class="col-sm-12 col-md-12">
                        <form
                            method="POST"
                            action="{{ route('users.update-password') }}"
                            enctype="multipart/form-data" id="form">
                            @csrf
                            @method( 'PUT' )
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-1 ps-md-2 pe-md-2">
                                                <div class="row pe-md-1">
                                                    <div class="col-md-12 pe-md-0">
                                                        <div class="mb-1 ps-md-0 pe-md-0">
                                                            <h4>{{ __('locale.change-password') }}</h4>
                                                            <hr />

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12 d-flex justify-content-center">
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 pe-md-0">
                                                <div class="mb-1 ps-md-2 pe-md-50">
                                                    <label class="form-label" for="password">{{ __('locale.password') }}</label>
                                                    <input
                                                        type="password"
                                                        id="password"
                                                        class="form-control @if ($errors->has('new_password')) {{ 'invalid' }} @endif"
                                                        name="new_password"
                                                        value="{{ old('new_password') }}"
                                                        placeholder="{{ __('locale.password') }}" />
                                                    @if ($errors->has('new_password'))
                                                        <div class="invalid-feedback">{{ $errors->first('new_password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 pe-md-0">
                                                <div class="mb-1 ps-md-2 pe-md-50">
                                                    <label class="form-label"
                                                        for="confirm_password">{{ __('locale.confirm-password') }}</label>
                                                    <input
                                                        type="password"
                                                        id="confirm_password"
                                                        class="form-control @if ($errors->has('confirm_password')) {{ 'invalid' }} @endif"
                                                        name="confirm_password"
                                                        value="{{ old('confirm_password') }}"
                                                        placeholder="{{ __('locale.confirm-password') }}" />
                                                    @if ($errors->has('confirm_password'))
                                                        <div class="invalid-feedback">{{ $errors->first('confirm_password') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 col-md-12">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="mb-1 px-md-2">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="row mt-2">
                                                            <div class="col-md-6">

                                                                </div>
                                                                <div class="col-md-6 d-flex justify-content-end">
                                                                    <a href="{{ route('users.profile') }}"
                                                                    class="btn btn-outline-primary">{{ __('locale.cancel') }}</a>
                                                                    <button type="submit"
                                                                    class="btn btn-primary ms-75">{{ __('locale.change-password') }}</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->

    <script src="{{ asset('js/scripts/forms/form-select2.js') }}"></script>
    <script src="{{ asset('js/scripts/forms/form-validation.js') }}"></script>
    <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}"></script>
    <script>
        const form = $('#form');
        if (form.length) {
            form.validate({
                rules: {
                    'title': {
                        maxlength: {
                            param: 200
                        }
                    },
                    'subtitle': {
                        maxlength: {
                            param: 200
                        }
                    },
                },
                messages: {
                    'title': {
                        maxlength: "{{ __('validate-form.subtitle-max') }}"
                    },
                    'subtitle': {
                        maxlength: "{{ __('validate-form.subtitle-max') }}"
                    },
                }
            });
        }
        var isRtl = $('html').attr('data-textdirection') === 'rtl';
        $('#publicize_at').on('change', function() {
            let publicizeAt = $('#publicize_at').val();
            publicizeAt = new Date(publicizeAt);
            let day = publicizeAt.getDate();
            let month = publicizeAt.getMonth() + 1;
            let year = publicizeAt.getFullYear();
            let hours = publicizeAt.getHours();
            let minutes = publicizeAt.getMinutes();
            publicizeAt = `${day}/${month}/${year} ${hours}:${minutes}`;
            setTimeout(function() {
                toastr['success'](
                    'Success',
                    `{{ __('locale.publicize-gtm') }} ${publicizeAt}`, {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: isRtl
                    }
                );
            }, 500);
        });

        const previewFile = (event, idImage) => {
            let id = event.id;
            //console.log( event.id );
            var preview = document.getElementById(idImage);
            var file = document.getElementById(id).files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "";
            }
        }

        const confirmDelete = () => {
            $( '#form-delete' ).submit();
        }
    </script>
@endsection
