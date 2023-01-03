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

@section('title', __('locale.profile'))

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
                        <form method="POST" action="{{ route('users.update-profile', [$user->id]) }}"
                            enctype="multipart/form-data" id="form" autocomplete="off">
                            @csrf
                            @method( 'PUT' )
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
                                                                    <h4 class="link-dark">{{ __('locale.profile') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="hidden" name="role" value="{{ $user->role_id }}">
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
                                                    name="name" value="{{ $user->name }}"
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
                                                                            class="form-control bg-white"
                                                                            type="text"
                                                                            name=""
                                                                            id=""
                                                                            value=""
                                                                            placeholder="{{__('locale.choose-file')}}"
                                                                            onclick="$('#file').trigger('click')"
                                                                            readonly
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
                                                            <div
                                                                class="col-md-4
                                                                    image
                                                                    overflow-hidden
                                                                    d-flex
                                                                    justify-content-center"
                                                                id="image">
                                                                <input type="hidden" id="removeImage" name="removeImage" value="false">
                                                                <input type="hidden" id="defaultImage" name="defaultImage" value="{{ asset('images/no-image.png') }}">
                                                                <div
                                                                    class="shade
                                                                        align-items-center
                                                                        d-flex
                                                                        justify-content-center"
                                                                    id="shade"
                                                                    style="display: none!important">
                                                                    <i class="font-large-1 text-white" data-feather='trash-2'></i>
                                                                </div>
                                                                @if ($user->avatar)
                                                                    <img id="image-preview"
                                                                        src="data:image/jpg;base64,{{ base64_encode($user->avatar) }}"
                                                                        alt="Image preview...">
                                                                @else
                                                                    <img id="image-preview" class=""
                                                                    src="{{ asset('images/no-image.png') }}"
                                                                    alt="Image preview..." />
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-sm-12 col-md-12 pe-md-0 d-flex">
                                            <div class="col-md-6">
                                                <div class="mb-1 ps-md-2 pe-md-50">
                                                    <label class="form-label"
                                                        for="password">{{ __('locale.password') }}</label>
                                                    <input type="password" id="password"
                                                        class="form-control disabled @if ($errors->has('password')) {{ 'invalid' }} @endif" name="email"
                                                        value="{{ 'passwordextens' }}"
                                                        placeholder="{{ __('locale.password') }}"
                                                        style="-webkit-box-shadow: 0 0 0 1000px #efefef inset!important;" />
                                                    @if ($errors->has('password'))
                                                        <div class="invalid-feedback">{{ $errors->first('password') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                                <div>
                                                    <a
                                                        href="{{ route('users.change-password') }}">{{ __('locale.change-password') }}</a>
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
                                                <input type="email" id="email" class="form-control disabled border-dark py-1 @if ($errors->has('email')) {{ 'invalid' }} @endif"
                                                    name="email" value="{{ $user->email }}"
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
                                                                <button
                                                                    type="submit"
                                                                    class="btn btn-primary ms-75 round py-1"
                                                                    style="color: #282828!important;"
                                                                    >
                                                                    {{ __('locale.to-register') }}
                                                                </button>
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
    @if ($message = Session::get('message'))
        @if ($message == 'password change')
            <script>
                var isRtl = $('html').attr('data-textdirection') === 'rtl';
                let message = "{{ $message }}" == "password change" ? "{{ __('locale.password-change') }}" : "";
                setTimeout(function() {
                    toastr['success'](
                        'Success',
                        `${message}`, {
                            closeButton: true,
                            tapToDismiss: false,
                            rtl: isRtl
                        }
                    );
                }, 500);

                if ("{{ $message }}" == "password change") {
                    setTimeout(function() {
                        $('#logout-form').submit();
                    }, 3000);
                }
            </script>
        @else
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
    @endif
    <script>
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
            $('#form-delete').submit();
        }

        $('#image').on( 'mouseover', function () {
           $( '#shade' ).css({'display':'flex'});
           //console.log('mouseover');
        } );
        $('#image').on( 'mouseout', function () {
            $( '#shade' ).attr('style','display: none!important;');
            //console.log('mouseout');
        } );

        $( '#shade' ).on( 'click', function () {
            console.log( 'click' );
            let src = $('#defaultImage').val();
            console.log( src );
            $('#image-preview').attr( 'src', src );
            $('#removeImage').val(true);
        } );
    </script>
@endsection
