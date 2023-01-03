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
    <style>
        .container-back {
            display: inline-flex;
            flex-wrap: nowrap;
            align-items: center;
            vertical-align: middle;
        }
        .container-back h4 {
            padding: 0;
            margin: 0;
        }

        .arrow-back {
            width: 30px!important;
            height: 30px!important;
            padding: 0;
            margin: 0;
        }
        .shade {
            width: 100%;
            height: 100%;
            background: rgba(94.9, 94.9, 94.9,0.1);
            position: absolute;
            top: 0;
            left: 0;
        }
        .invalid-feedback {
            display: block!important;
        }
        .invalid {
            border-color: #ea5455!important;
            padding-right: calc(1.45em + 1.142rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ea5455'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ea5455' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.3625em + 0.2855rem) center;
            background-size: calc(0.725em + 0.571rem) calc(0.725em + 0.571rem);

        }

        .invalid:focus {
            border-color: #ea5455 !important;
            box-shadow: 0 0 0 0.25rem rgba(234, 84, 85, 0.25)!important;
        }
    </style>
@endsection

@section('title', __('locale.create-theme'))

@section('content')
    <section>
        <div class="row">
            <div class="col-sm-12 col-md-12 mb-1 ps-md-2 pe-md-50">
                <div class="row d-flex">
                    <a href="{{ route('themes.index') }}">
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
            <form method="POST" action="{{ route('themes.store') }}" enctype="multipart/form-data" id="form">
                <input
                    type="hidden"
                    id="urlGetSections"
                    name="urlGetSections"
                    value="{{ route( 'sections.get-json' ) }}">
                <input
                    type="hidden"
                    name="urlGetSlug"
                    id="urlGetSlug"
                    value="{{ route('themes.get-slug') }}">
                @csrf
                <div class="col-sm-10 col-md-12">
                    <div class="card px-50 pb-2">
                        <div class="col-sm-12 col-md-12">
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
                                                                    <h4 class="link-dark">
                                                                        {{ __('locale.new-theme') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                        <div class="mb-1 ps-md-0 pe-md-0">
                                                            <hr />
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
                                                <label class="form-label link-dark"
                                                    for="name">{{ __('locale.name') }}</label>
                                                <input type="text" id="name"
                                                    class="form-control border-dark py-1 @if ($errors->has('name')) {{ 'invalid' }} @endif"
                                                    name="name" value="{{ old('name') }}"
                                                    placeholder="{{ __('locale.name') }}" />
                                                @if ($errors->has('name'))
                                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 pe-md-0">
                                            <div class="mb-1 ps-md-2 pe-md-50">
                                                <label class="form-label link-dark"
                                                    for="slug">{{ __('locale.slug') }}</label>
                                                <input type="text" id="slug"
                                                    class="form-control border-dark py-1 @if ($errors->has('slug')) {{ 'invalid' }} @endif"
                                                    name="slug" value="{{ old('slug') }}"
                                                    placeholder="{{ __('locale.slug') }}" />
                                                @if ($errors->has('slug'))
                                                    <div class="invalid-feedback">{{ $errors->first('slug') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 pe-md-0">
                                            <div class="mb-1 ps-md-2 pe-md-50">
                                                <label class="form-label link-dark"
                                                    for="indexed">{{ __('locale.do-you-want-to-index-this-content') }}</label>
                                                <input type="hidden" id="oldIndexed" value="{{ old('indexed') }}">
                                                <div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="indexed" id="yes"
                                                            value="1" @if ( old( 'indexed' ) == '' || old( 'indexed' ) == 1 ) {{'checked'}} @endif />
                                                        <label class="form-check-label"
                                                            for="yes">{{ __('locale.yes') }}</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="indexed" id="no"
                                                            value="0" @if ( old( 'indexed' ) != '' && old( 'indexed' ) == 0 ) {{'checked'}} @endif />
                                                        <label class="form-check-label"
                                                            for="no">{{ __('locale.no') }}</label>
                                                    </div>
                                                </div>
                                                @if ($errors->has('indexed'))
                                                    <div class="invalid-feedback">{{ $errors->first('indexed') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-12">
                    <div class="card px-50 pb-2">
                        <div class="col-sm-12 col-md-12">
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
                                                                    <h4 class="link-dark">
                                                                        {{ __('locale.visibility') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 d-flex justify-content-end">
                                                                @if ($errors->has('visibility'))
                                                                    <div class="invalid-feedback">{{ $errors->first('visibility') }}</div>
                                                                @endif
                                                                <div class="form-check form-check-primary form-switch">
                                                                    <input
                                                                        type="checkbox"
                                                                        class="form-check-input @if ($errors->has('visibility')) {{ 'invalid' }} @endif"
                                                                        id="visibility"
                                                                        name="visibility" @if ( old( 'visibility' ) == 'on' ) {{'checked'}} @endif />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="mb-1 ps-md-0 pe-md-0">
                                                            <hr />
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
                                                <p class="link-dark">
                                                    {{ __('locale.active-visibility') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-12">
                    <div class="card px-50 pb-2">
                        <div class="col-sm-12 col-md-12">
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
                                                                    <h4 class="link-dark">
                                                                        {{ __('locale.sections') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                        <div class="mb-1 ps-md-0 pe-md-0">
                                                            <hr />
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
                                                <p class="link-dark">
                                                    {{ __('locale.sections-message') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 pe-md-0">
                                            <div class="mb-1 ps-md-2 pe-md-50">
                                                @php
                                                    $sections = '';
                                                    if ( old('sections') ) {
                                                        foreach ( old('sections') as $section ) {
                                                            $sections .= $section . ',';
                                                        }
                                                    }
                                                @endphp
                                                <input type="hidden" id="oldSections" value="{{ $sections }}">
                                                <div id="seccions-content" class="d-flex @if ($errors->has('sections')) {{ 'form-control invalid' }} @endif"></div>
                                                @if ($errors->has('sections'))
                                                    <div class="invalid-feedback">{{ $errors->first('sections') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-12">
                    <div class="card px-50 pb-2 position-relative">
                        <div
                            class="shade rounded-25"
                            id="shade">
                        </div>
                        <div class="col-sm-12 col-md-12">
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
                                                                    <h4 class="">
                                                                        {{ __('locale.start-position') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                            </div>
                                                        </div>
                                                        <div class="mb-1 ps-md-0 pe-md-0">
                                                            <hr />
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
                                                <p class="">
                                                    {{ __('locale.select-position') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 pe-md-0">
                                            <div class="mb-1 ps-md-2 pe-md-50">
                                                <input type="hidden" id="start_position_flat" name="start_position_flat" value="false">
                                                <select
                                                    class="form-select py-1 @if ($errors->has('start_position')) {{ 'invalid' }} @endif"
                                                    id="start_position"
                                                    name="start_position">
                                                    <option value="1" @if (old('start_position') == 1) {{ 'selected' }} @endif>1</option>
                                                    <option value="2" @if (old('start_position') == 2) {{ 'selected' }} @endif>2</option>
                                                    <option value="3" @if (old('start_position') == 3) {{ 'selected' }} @endif>3</option>
                                                    <option value="4" @if (old('start_position') == 4) {{ 'selected' }} @endif>4</option>
                                                    <option value="5" @if (old('start_position') == 5) {{ 'selected' }} @endif>5</option>
                                                    <option value="6" @if (old('start_position') == 6) {{ 'selected' }} @endif>6</option>
                                                    <option value="7" @if (old('start_position') == 7) {{ 'selected' }} @endif>7</option>
                                                    <option value="8" @if (old('start_position') == 8) {{ 'selected' }} @endif>8</option>
                                                    <option value="9" @if (old('start_position') == 9) {{ 'selected' }} @endif>9</option>
                                                </select>
                                                @if ($errors->has('start_position'))
                                                    <div class="invalid-feedback">{{ $errors->first('start_position') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12">
                    <div class="col-sm-12 col-md-12">
                        <div class="mb-1 px-md-2">
                            <div class="row">
                                <div class="col-sm-12 px-0">
                                    <div class="row">
                                        <div class="col-md-6">

                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end px-0">
                                            <a href="{{ route('themes.index') }}"
                                                class="btn btn-outline-primary round py-1">{{ __('locale.cancel') }}</a>
                                            <button type="submit"
                                                class="btn btn-primary ms-75 round py-1"
                                                style="color: #282828!important;"
                                                >{{ __('locale.create') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
    @if ($error = Session::get('error'))
        <script>
            var isRtl = $('html').attr('data-textdirection') === 'rtl';
            setTimeout(function() {
                toastr['error'](
                    'Error',
                    "{{ $error }}", {
                        closeButton: true,
                        tapToDismiss: false,
                        rtl: isRtl
                    }
                );
            }, 500);
        </script>
    @endif
    <script>

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

        const getSections = async () => {
            let url = $('#urlGetSections').val();
            //console.log( title );
            await $.ajax({
                url: url,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    //console.log( response );
                    $('#seccions-content').empty();
                    let oldSections = $('#oldSections').val();
                    oldSections = oldSections.split(',');
                    console.log( oldSections );
                    response.forEach((element, index) => {
                        const {
                            id,
                            code,
                            name
                        } = element;
                        let checked = '';
                        if ( oldSections.includes( '' + id ) ) {
                            checked = 'checked';
                        }
                        let option = `<div class="width-100 form-check form-check-inline d-flex align-items-center">
                            <input
                                class="form-check-input py-md-75 px-md-75 rounded-25 ps-75 sections"
                                type="checkbox"
                                id="${code}"
                                value="${id}"
                                name="sections[]"
                                ${checked}
                            />
                            <label class="form-check-label ps-md-50" for="${code}">${name}</label>
                        </div>`;
                        $('#seccions-content').append(option);
                    });
                    disableAppearHome();
                },
                error: function(response) {
                    console.log(response);
                },
            });
        }

        $('#name').on('change', function () {
            let name = $('#name').val();
            let urlGetSlug = $('#urlGetSlug').val();
            //console.log( title );
            $.ajax({
                url: urlGetSlug,
                method: 'GET',
                data: { name: name },
                dataType: 'JSON',
                success: function (response) {
                    //console.log( data );
                    $('#slug').val(response)
                },
                error: function (response) {
                    console.log(response);
                },
            });
        });


        const init = async () => {
            await getSections();
        }

        init();

        $( document ).ready( function () {
            $( '.sections' ).on( 'click', function () {
                disableAppearHome();
            } );
        } );

        const disableAppearHome = () => {
            let home = $( '#INI' ).is( ':checked' );
            console.log( home );
            if ( home ) {
                $( '#shade' ).attr( 'style', 'display: none!important' );
                $( '#start_position_flat' ).val( 'true' );
            } else {
                $( '#shade' ).attr( 'style', '' );
                $( '#start_position_flat' ).val( 'false' );
            }
        }
    </script>
@endsection