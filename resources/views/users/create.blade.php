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

@section('title', __('locale.create-user'))

@section('content')
    <section>
        <div class="row">
            <div class="col-sm-12 col-md-12 mb-1 ps-md-2 pe-md-50">
                <div class="row d-flex">
                    <a href="{{ route('users.index') }}">
                        <div class="col-md-4 container-back">
                            <i data-feather='chevron-left' class="arrow-back"></i>
                            <h4 class="font-medium-5 align-middle">
                                {{ __('locale.back') }}
                            </h4>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10 col-md-12">
                <div class="card px-50">
                    <div class="col-sm-12 col-md-12">
                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data" id="form">
                            <input type="hidden" id="urlGetRoles" name="urlGetRoles" value="{{ route( 'roles.get-json' ) }}">
                            @csrf
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-1 ps-md-50 pe-md-50">
                                                <div class="row pe-md-0 ps-md-0">
                                                    <div class="col-md-12 pe-md-0 ps-md-0">
                                                        <div class="mb-2 ps-md-2 pe-md-2 d-flex">
                                                            <div class="col-md-6">
                                                                <div class="ps-md-50 pe-md-50">
                                                                    <h4 class="link-dark">{{ __('locale.new-user') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="hidden" id="oldRole" value="{{ old('role') }}">
                                                                <div id="container-roles">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-1 ps-md-0 pe-md-0">
                                                            <hr/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 pe-md-0">
                                            <div class="mb-1 ps-md-2 pe-md-50">
                                                <label class="form-label link-dark" for="name">{{ __('locale.name') }}</label>
                                                <input type="text" id="name" class="form-control border-dark py-1 @if ($errors->has('name')) {{ 'invalid' }} @endif"
                                                    name="name" value="{{ old('name') }}"
                                                    placeholder="{{ __('locale.name') }}" />
                                                @if ($errors->has('name'))
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12 col-md-12 pe-md-0">
                                            <div class="mb-1 ps-md-2 pe-md-50">
                                                <label class="form-label link-dark" for="image">{{ __('locale.image-user') }}</label>
                                                <div class="form-control ps-md-0">
                                                    <div class="col-sm-12 col-md-12 px-md-2 py-md-1">
                                                        <div class="d-flex justify-content-around">
                                                            <div class="col-md-8 ps-1 py-50">
                                                                <div class="align-middle py-2">
                                                                    <div>
                                                                        <p>{{ __('locale.image-proportion-user') }}</p>
                                                                    </div>
                                                                    <div class="input-group">
                                                                        <input
                                                                            class="form-control"
                                                                            type="text"
                                                                            name=""
                                                                            id=""
                                                                            placeholder="{{__('locale.choose-file')}}"
                                                                            onclick="$('#file').trigger('click')"
                                                                            >
                                                                        <input type="file" id="file"
                                                                        class="form-control file-input @if ($errors->has('image')) {{ 'invalid' }} @endif"
                                                                        name="image" value="{{ old('image') }}"
                                                                        placeholder="{{ __('locale.image') }}"
                                                                        title={{__('locale.choose-file')}}
                                                                        onchange="previewFile( this, 'image-preview' )"/>
                                                                        <button class="btn btn-primary" id="search" style="color: #282828!important;" type="button" onclick="$('#file').trigger('click')">{{__('locale.search')}}</button>
                                                                    </div>
                                                                    @if ($errors->has('image'))
                                                                        <div class="invalid-feedback">
                                                                            {{ $errors->first('image') }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 image overflow-hidden d-flex justify-content-center">
                                                                <img id="image-preview" class=""
                                                                    src="{{ asset('images/no-image.png') }}"
                                                                    alt="Image preview..." />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="email">{{ __('locale.email') }}</label>
                                                <input type="email" id="email" class="form-control border-dark py-1 @if ($errors->has('email')) {{ 'invalid' }} @endif"
                                                    name="email" value="{{ old('email') }}"
                                                    placeholder="{{ __('locale.email') }}" />
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                                @endif
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
                                                                <a href="{{ route('users.index') }}"
                                                                    class="btn btn-outline-primary">{{ __('locale.cancel') }}</a>
                                                                <button type="submit"
                                                                    class="btn btn-primary ms-75"
                                                                    style="color: #282828!important;"
                                                                    >{{ __('locale.to-register') }}</button>
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

        const previewFile = (event, idImage) =>  {
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

        const getRoles = async () => {
            let urlGetRoles = $('#urlGetRoles').val();
            //console.log( title );
            await $.ajax({
                url: urlGetRoles,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    //console.log( response );
                    $('#container-roles').empty();
                    Object.entries(response).forEach(([key, value]) => {
                        let oldLevel = $('#oldRole').val();
                        //console.log( oldLevel );
                        let checked = '';
                        if (oldLevel == key) {
                            checked = 'checked';
                        } else if (oldLevel === 0 || oldLevel === '') {
                            if (key == 1) {
                                checked = 'checked';
                            }
                        }
                        let option = `<div class="form-check form-check-inline">
                        <input
                            class="form-check-input"
                            type="radio"
                            name="role"
                            id="${value.toLowerCase()}"
                            value="${key}"
                            ${checked}
                            />
                            <label class="form-check-label" for="${value.toLowerCase()}">${value}</label>
                        </div>`;
                        $('#container-roles').append(option);
                    });
                },
                error: function(response) {
                    console.log(response);
                },
            });
        }

        const init = async () => {
            await getRoles();
        }

        init();
    </script>
@endsection
