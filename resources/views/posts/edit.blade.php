@extends('layouts/contentLayoutMaster')

@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset('vendors/css/forms/select/select2.min.css') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Inconsolata&family=Roboto+Slab&family=Slabo+27px&family=Sofia&family=Ubuntu+Mono&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendors/css/editors/quill/katex.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/editors/quill/monokai-sublime.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/editors/quill/quill.snow.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/css/editors/quill/quill.bubble.css') }}">
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
            width: 30px !important;
            height: 30px !important;
            padding: 0;
            margin: 0;
        }

        .shade {
            width: 100%;
            height: 100%;
            background: rgba(94.9, 94.9, 94.9, 0.1);
            position: absolute;
            top: 0;
            left: 0;
        }

        .invalid-feedback {
            display: block !important;
        }

        .invalid {
            border-color: #ea5455 !important;
            padding-right: calc(1.45em + 1.142rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ea5455'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ea5455' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.3625em + 0.2855rem) center;
            background-size: calc(0.725em + 0.571rem) calc(0.725em + 0.571rem);

        }

        .invalid:focus {
            border-color: #ea5455 !important;
            box-shadow: 0 0 0 0.25rem rgba(234, 84, 85, 0.25) !important;
        }

        .select2-selection {
            padding-bottom: .55rem !important;
            padding-top: .55rem !important;
            border: 1px solid #282828 !important;
        }

        .select2-selection .select2-selection__rendered .select2-selection__choice {
            padding: .1rem .3rem !important;
            margin: 0px !important;
            margin-top: .30rem !important;
            margin-right: .35rem !important;
        }

        /* Set dropdown font-families */
        .ql-toolbar .ql-font span[data-label="Sailec Light"]::before {
            font-family: "Sailec Light";
        }

        .ql-toolbar .ql-font span[data-label="Sofia Pro"]::before {
            font-family: "Sofia";
        }

        .ql-toolbar .ql-font span[data-label="Slabo 27px"]::before {
            font-family: "Slabo 27px";
        }

        .ql-toolbar .ql-font span[data-label="Roboto Slab"]::before {
            font-family: "Roboto Slab";
        }

        .ql-toolbar .ql-font span[data-label=Inconsolata]::before {
            font-family: "Inconsolata";
        }

        .ql-toolbar .ql-font span[data-label="Ubuntu Mono"]::before {
            font-family: "Ubuntu Mono";
        }

        /* Set content font-families */
        .ql-font-sofia {
            font-family: "Sofia";
        }

        .ql-font-slabo {
            font-family: "Slabo 27px";
        }

        .ql-font-roboto {
            font-family: "Roboto Slab";
        }

        .ql-font-inconsolata {
            font-family: "Inconsolata";
        }

        .ql-font-ubuntu {
            font-family: "Ubuntu Mono";
        }

        .ql-toolbar {
            border-color: #d8d6de !important;
        }

        .ql-toolbar .ql-formats:focus,
        .ql-toolbar .ql-formats *:focus {
            outline: 0;
        }

        .ql-toolbar .ql-formats .ql-picker-label:hover,
        .ql-toolbar .ql-formats .ql-picker-label:focus,
        .ql-toolbar .ql-formats button:hover,
        .ql-toolbar .ql-formats button:focus {
            color: #7367f0 !important;
        }

        .ql-toolbar .ql-formats .ql-picker-label:hover .ql-stroke,
        .ql-toolbar .ql-formats .ql-picker-label:focus .ql-stroke,
        .ql-toolbar .ql-formats button:hover .ql-stroke,
        .ql-toolbar .ql-formats button:focus .ql-stroke {
            stroke: #7367f0 !important;
        }

        .ql-toolbar .ql-formats .ql-picker-label:hover .ql-fill,
        .ql-toolbar .ql-formats .ql-picker-label:focus .ql-fill,
        .ql-toolbar .ql-formats button:hover .ql-fill,
        .ql-toolbar .ql-formats button:focus .ql-fill {
            fill: #7367f0 !important;
        }

        .ql-toolbar .ql-formats .ql-picker-label.ql-active,
        .ql-toolbar .ql-formats button.ql-active {
            color: #7367f0 !important;
        }

        .ql-toolbar .ql-formats .ql-picker-item.ql-selected {
            color: #7367f0 !important;
        }

        .ql-toolbar .ql-formats .ql-picker-options .ql-picker-item:hover {
            color: #7367f0 !important;
        }

        .ql-toolbar .ql-formats .ql-picker-options .ql-active {
            color: #7367f0 !important;
        }

        .ql-bubble .ql-picker {
            color: #fff !important;
        }

        .ql-bubble .ql-stroke {
            stroke: #fff !important;
        }

        .ql-bubble .ql-fill {
            fill: #fff !important;
        }

        .ql-container {
            border-color: #d8d6de !important;
            font-family: "Montserrat", Helvetica, Arial, serif;
        }

        .ql-editor a {
            color: #7367f0;
        }

        .ql-picker {
            color: #5e5873 !important;
        }

        .ql-stroke {
            stroke: #5e5873 !important;
        }

        .ql-active .ql-stroke {
            stroke: #7367f0 !important;
        }

        .ql-active .ql-fill {
            fill: #7367f0 !important;
        }

        .ql-fill {
            fill: #5e5873 !important;
        }

        .ql-toolbar {
            border-top-right-radius: 1rem !important;
            border-top-left-radius: 1rem !important;
        }

        .editor,
        .ql-container {
            border-bottom-right-radius: 1rem !important;
            border-bottom-left-radius: 1rem !important;
        }

        .ql-toolbar+.ql-container,
        .ql-container+.ql-toolbar {
            border-bottom-right-radius: 0.357rem;
            border-bottom-left-radius: 0.357rem;
            border-top-right-radius: unset;
            border-top-left-radius: unset;
        }

        .dark-layout .quill-toolbar,
        .dark-layout .ql-toolbar {
            background-color: #283046;
            border-color: #3b4253 !important;
        }

        .dark-layout .quill-toolbar .ql-picker,
        .dark-layout .ql-toolbar .ql-picker {
            color: #fff !important;
        }

        .dark-layout .quill-toolbar .ql-stroke,
        .dark-layout .ql-toolbar .ql-stroke {
            stroke: #fff !important;
        }

        .dark-layout .quill-toolbar .ql-fill,
        .dark-layout .ql-toolbar .ql-fill {
            fill: #fff !important;
        }

        .dark-layout .quill-toolbar .ql-picker-options,
        .dark-layout .quill-toolbar .ql-picker-label,
        .dark-layout .ql-toolbar .ql-picker-options,
        .dark-layout .ql-toolbar .ql-picker-label {
            background-color: #283046;
        }

        .dark-layout .quill-toolbar .ql-picker-options .ql-active,
        .dark-layout .quill-toolbar .ql-picker-label .ql-active,
        .dark-layout .ql-toolbar .ql-picker-options .ql-active,
        .dark-layout .ql-toolbar .ql-picker-label .ql-active {
            color: #7367f0 !important;
        }

        .dark-layout .ql-active .ql-stroke {
            stroke: #7367f0 !important;
        }

        .dark-layout .ql-active .ql-fill {
            fill: #7367f0 !important;
        }

        .dark-layout .ql-bubble .ql-toolbar {
            background: #3b4253;
            border-radius: 2rem;
        }

        .dark-layout .ql-container {
            border-color: #3b4253 !important;
            background-color: #283046;
        }

        .dark-layout .ql-editor .ql-syntax {
            background-color: #161d31;
        }

        .dark-layout .ql-editor.ql-blank:before {
            color: #b4b7bd;
        }

        [data-textdirection=rtl] .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) i,
        [data-textdirection=rtl] .ql-snow .ql-picker:not(.ql-color-picker):not(.ql-icon-picker) svg {
            left: auto !important;
            right: 0;
        }


        .ql-container.ql-snow {
            border: 3px solid #ccc !important;
            border-top: none !important;
        }

        .ql-toolbar.ql-snow {
            border: 3px solid #ccc !important;
            border-bottom: none !important;
        }

    </style>
@endsection

@section('title', __('locale.edit-post'))

@section('content')
    <section>
        <div class="row">
            <form method="POST" action="{{ route('posts.update', [$post->id]) }}" enctype="multipart/form-data" id="form">
                <input type="hidden" name="urlGetSlug" id="urlGetSlug" value="{{ route('posts.get-slug') }}">
                <input type="hidden" name="urlGetStatus" id="urlGetStatus" value="{{ route('status.get-json') }}">
                <input type="hidden" name="urlGetThemes" id="urlGetThemes" value="{{ route('themes.get-json') }}">
                <input type="hidden" name="urlGetTags" id="urlGetTags" value="{{ route('tags.get-list-json') }}">
                <input type="hidden" id="urlGetPostsTypes" name="urlGetPostsTypes"
                    value="{{ route('posts-types.get-json') }}">
                @method( 'PUT' )
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
                                                                        {{ __('locale.post-type') }}</h4>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <input type="hidden" id="oldPostType"
                                                                    value="{{ $post->entry_type_id }}">
                                                                <div id="container-post-type">
                                                                </div>
                                                                @if ($errors->has('post_type'))
                                                                    <div class="invalid-feedback">
                                                                        {{ $errors->first('post_type') }}</div>
                                                                @endif
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
                                                <label class="form-label link-dark" for="title">{{ __('locale.title') }}
                                                    *</label>
                                                <input type="text" id="title"
                                                    class="form-control border-dark py-1 @if ($errors->has('title')) {{ 'invalid' }} @endif"
                                                    name="title" value="{{ $post->entrylanguage[0]->title }}"
                                                    placeholder="{{ __('locale.title') }}" />
                                                @if ($errors->has('title'))
                                                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="status">{{ __('locale.status') }}</label>
                                                <input type="hidden" id="oldStatus" value="{{ $post->status_id }}">
                                                <select class="form-select border-dark py-1" tabindex="-1" id="status"
                                                    name="status">
                                                    <option>Published</option>
                                                </select>
                                                @if ($errors->has('status'))
                                                    <div class="invalid-feedback">{{ $errors->first('status') }}
                                                    </div>
                                                @endif
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
                                                    for="subtitle">{{ __('locale.subtitle') }}
                                                    ({{ __('locale.optional') }})</label>
                                                <input type="text" id="subtitle"
                                                    class="form-control border-dark py-1 @if ($errors->has('subtitle')) {{ 'invalid' }} @endif"
                                                    name="subtitle" value="{{ $post->entrylanguage[0]->subtitle }}"
                                                    placeholder="{{ __('locale.subtitle') }}" />
                                                @if ($errors->has('subtitle'))
                                                    <div class="invalid-feedback">{{ $errors->first('subtitle') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="meta_description">{{ __('locale.meta-description') }}</label>
                                                <input type="text" id="meta_description"
                                                    class="form-control border-dark py-1 @if ($errors->has('meta_description')) {{ 'invalid' }} @endif"
                                                    name="meta_description"
                                                    value="{{ $post->entrylanguage[0]->meta_description }}"
                                                    placeholder="{{ __('locale.meta-description') }}" />
                                                @if ($errors->has('meta_description'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('meta_description') }}</div>
                                                @endif
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
                                                    for="themes">{{ __('locale.themes') }}</label>
                                                @php
                                                    $themes = '';
                                                    /* if ($post->theme_id) {
                                                                                                                                                                foreach ($post->theme_id as $theme) {
                                                                                                                                                                    $themes .= $theme . ',';
                                                                                                                                                                }
                                                                                                                                                            } */
                                                    $themes = $post->theme_id . ',';
                                                @endphp
                                                <input type="hidden" id="oldThemes" value="{{ $themes }}">
                                                <select
                                                    class="form-select select2 border-dark py-1 @if ($errors->has('themes')) {{ 'invalid' }} @endif"
                                                    id="themes" name="themes[]" multiple>
                                                    <option value="bitcoin">Bitconin</option>
                                                    <option value="moneda">Moneda</option>
                                                </select>
                                                @if ($errors->has('themes'))
                                                    <div class="invalid-feedback">{{ $errors->first('themes') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="seo_title">{{ __('locale.seo_title') }}</label>
                                                <input type="text" id="seo_title"
                                                    class="form-control border-dark py-1 @if ($errors->has('seo_title')) {{ 'invalid' }} @endif"
                                                    name="seo_title" value="{{ $post->entrylanguage[0]->seo_title }}"
                                                    placeholder="{{ __('locale.seo_title') }}" />
                                                @if ($errors->has('seo_title'))
                                                    <div class="invalid-feedback">{{ $errors->first('seo_title') }}</div>
                                                @endif
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
                                                    for="slug">{{ __('locale.slug') }}</label>
                                                <input type="text" id="slug"
                                                    class="form-control border-dark py-1 @if ($errors->has('slug')) {{ 'invalid' }} @endif"
                                                    name="slug" value="{{ $post->entrylanguage[0]->slug }}"
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
                                        <label class="form-label link-dark"
                                            for="slug">{{ __('locale.do-you-want-to-index-this-content') }}</label>
                                        <div class="col-12 d-flex align-baseline py-1">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="indexed" id="yes"
                                                    value="1"
                                                    @if ($post->index_content == 1) {{ 'checked' }} @endif />
                                                <label class="form-check-label" for="yes">{{ __('locale.yes') }}</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="indexed" id="no"
                                                    value="0"
                                                    @if ($post->index_content == 0) {{ 'checked' }} @endif />
                                                <label class="form-check-label" for="no">{{ __('locale.no') }}</label>
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
                                                    for="header1">{{ __('locale.header1') }}
                                                    ({{ __('locale.optional') }})</label>
                                                <input type="text" id="header1"
                                                    class="form-control border-dark py-1 @if ($errors->has('header1')) {{ 'invalid' }} @endif"
                                                    name="header1" value="{{ $post->entrylanguage[0]->h1 }}"
                                                    placeholder="{{ __('locale.header1') }}" />
                                                @if ($errors->has('header1'))
                                                    <div class="invalid-feedback">{{ $errors->first('header1') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="header2">{{ __('locale.header2') }}
                                                    ({{ __('locale.optional') }})</label>
                                                <input type="text" id="header2"
                                                    class="form-control border-dark py-1 @if ($errors->has('header2')) {{ 'invalid' }} @endif"
                                                    name="header2" value="{{ $post->entrylanguage[0]->h2 }}"
                                                    placeholder="{{ __('locale.header2') }}" />
                                                @if ($errors->has('header2'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('header2') }}</div>
                                                @endif
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
                                                    for="header3">{{ __('locale.header3') }}
                                                    ({{ __('locale.optional') }})</label>
                                                <input type="text" id="header3"
                                                    class="form-control border-dark py-1 @if ($errors->has('header3')) {{ 'invalid' }} @endif"
                                                    name="header3" value="{{ $post->entrylanguage[0]->h3 }}"
                                                    placeholder="{{ __('locale.header3') }}" />
                                                @if ($errors->has('header3'))
                                                    <div class="invalid-feedback">{{ $errors->first('header3') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="header2">{{ __('locale.header4') }}
                                                    ({{ __('locale.optional') }})</label>
                                                <input type="text" id="header4"
                                                    class="form-control border-dark py-1 @if ($errors->has('header4')) {{ 'invalid' }} @endif"
                                                    name="header4" value="{{ $post->entrylanguage[0]->h4 }}"
                                                    placeholder="{{ __('locale.header4') }}" />
                                                @if ($errors->has('header4'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('header4') }}</div>
                                                @endif
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
                                                    for="tags">{{ __('locale.tags') }}</label>
                                                @php
                                                    $tags = '';
                                                    if ($post->entrytag) {
                                                        foreach ($post->entrytag as $tag) {
                                                            $tags .= $tag->tag_id . ',';
                                                        }
                                                    }
                                                @endphp
                                                <input type="hidden" id="oldTags" value="{{ $tags }}">
                                                <select
                                                    class="form-select select2 border-dark py-1 @if ($errors->has('tags')) {{ 'invalid' }} @endif"
                                                    id="tags" name="tags[]" multiple>
                                                    <option value="bitcoin">Bitconin</option>
                                                    <option value="moneda">Moneda</option>
                                                </select>
                                                @if ($errors->has('tags'))
                                                    <div class="invalid-feedback">{{ $errors->first('tags') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-12" id="row-videos">
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
                                                                        {{ __('locale.video') }}</h4>
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
                                                    for="url_video_youtube">{{ __('locale.link-preview') }}
                                                    ({{ __('locale.youtube') }})</label>
                                                <input type="text" id="url_video_youtube"
                                                    class="form-control border-dark py-1 @if ($errors->has('url_video_youtube')) {{ 'invalid' }} @endif"
                                                    name="url_video_youtube"
                                                    value="{{ $post->entrylanguage[0]->url_video_youtube }}"
                                                    placeholder="{{ __('locale.link-preview') }}" />
                                                @if ($errors->has('url_video_youtube'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('url_video_youtube') }}</div>
                                                @endif
                                                <p><small class="text-black text-opacity-75">enlace.vimeo.com</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 ps-md-0">
                                            <div class="mb-1 ps-md-50 pe-md-2">
                                                <label class="form-label link-dark"
                                                    for="url_video_vimeo">{{ __('locale.full-video-link') }}
                                                    ({{ __('locale.vimeo') }})</label>
                                                <input type="text" id="url_video_vimeo"
                                                    class="form-control border-dark py-1 @if ($errors->has('url_video_vimeo')) {{ 'invalid' }} @endif"
                                                    name="url_video_vimeo"
                                                    value="{{ $post->entrylanguage[0]->url_video_vimeo }}"
                                                    placeholder="{{ __('locale.link-preview') }}" />
                                                </select>
                                                @if ($errors->has('url_video_vimeo'))
                                                    <div class="invalid-feedback">
                                                        {{ $errors->first('url_video_vimeo') }}
                                                    </div>
                                                @endif
                                                <p><small class="text-black text-opacity-75">enlace.vimeo.com</small></p>
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
                                                                        {{ __('locale.audio') }}</h4>
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
                                                    for="url_audio">{{ __('locale.link') }}
                                                    (¿{{ __('locale.spotify') }}, {{ __('locale.¡voox') }}?)</label>
                                                <input type="text" id="url_audio"
                                                    class="form-control border-dark py-1 @if ($errors->has('url_audio')) {{ 'invalid' }} @endif"
                                                    name="url_audio" value="{{ $post->entrylanguage[0]->url_audio }}"
                                                    placeholder="{{ __('locale.url-audio') }}" />
                                                @if ($errors->has('url_audio'))
                                                    <div class="invalid-feedback">{{ $errors->first('url_audio') }}
                                                    </div>
                                                @endif
                                                <p><small class="text-black text-opacity-75">enlace.vimeo.com</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-10 col-md-12" id="row-transcription">
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
                                                                        {{ __('locale.video-transcription') }}</h4>
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
                                <div class="col-sm-12 col-md-12">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="mb-1 px-md-2">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div id="full-wrapper">
                                                                <div id="full-container" class="rounded-pill">
                                                                    <input type="hidden" name="video_transcription"
                                                                        id="video_transcription"
                                                                        value="{{ $post->entrylanguage[0]->video_transcription }}" />
                                                                    <div class="editor height-200 @if ($errors->has('video_transcription')) {{ 'invalid' }} @endif"
                                                                        id="transcription">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('video_transcription'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('video_transcription') }}</div>
                                                    @endif
                                                </div>
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
                                                                        {{ __('locale.content') }}</h4>
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
                                <div class="col-sm-12 col-md-12">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="mb-1 px-md-2">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="row">
                                                            <div id="full-wrapper">
                                                                <div id="full-container">
                                                                    <input type="hidden" name="content" id="content"
                                                                        value="{{ $post->entrylanguage[0]->content }}" />
                                                                    <div class="editor height-200 @if ($errors->has('content')) {{ 'invalid' }} @endif"
                                                                        id="editor-content">

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('content'))
                                                        <div class="invalid-feedback">
                                                            {{ $errors->first('content') }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                            <a id="preview" href="" target="_blank"
                                                class="btn btn-flat-primary">{{ __('locale.preview') }}</a>
                                        </div>
                                        <div class="col-md-6 d-flex justify-content-end px-0">
                                            <a href="{{ route('posts.index') }}"
                                                class="btn btn-outline-primary round py-1">{{ __('locale.cancel') }}</a>
                                            <button type="submit" class="btn btn-primary ms-75 round py-1"
                                                style="color: #282828!important;">{{ __('locale.save') }}</button>
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
    <script src="{{ asset('vendors/js/editors/quill/katex.min.js') }}"></script>
    <script src="{{ asset('vendors/js/editors/quill/highlight.min.js') }}"></script>
    <script src="{{ asset('vendors/js/editors/quill/quill.min.js') }}"></script>
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

        $('#title').on('change', function() {
            let name = $('#title').val();
            let urlGetSlug = $('#urlGetSlug').val();
            //console.log( title );
            $.ajax({
                url: urlGetSlug,
                method: 'GET',
                data: {
                    name: name
                },
                dataType: 'JSON',
                success: function(response) {
                    //console.log( data );
                    $('#slug').val(response)
                },
                error: function(response) {
                    console.log(response);
                },
            });
        });

        $(document).ready(function() {
            $('.sections').on('click', function() {
                disableAppearHome();
            });
        });

        const disableAppearHome = () => {
            let home = $('#INI').is(':checked');
            console.log(home);
            if (home) {
                $('#shade').attr('style', 'display: none!important');
                $('#start_position_flat').val('true');
            } else {
                $('#shade').attr('style', '');
                $('#start_position_flat').val('false');
            }
        }

        (function(window, document, $) {
            'use strict';

            let transcription = new Quill('#full-container #transcription', {
                bounds: '#full-container .editor',
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: [
                        [
                            'bold',
                            'italic',
                            'underline', {
                                header: ['1', '2', '3', '4', '5', '6', '']
                            },
                        ],
                    ]
                },
                theme: 'snow'
            });

            transcription.on('text-change', function(delta, oldDelta, source) {
                $('#video_transcription').val(transcription.root.innerHTML);
            });

            let videoTranscription = $('#video_transcription').val();
            if (videoTranscription != '') {
                transcription.root.innerHTML = videoTranscription;
            }
        })(window, document, jQuery);

        (function(window, document, $) {
            'use strict';

            let editorContent = new Quill('#full-container #editor-content', {
                bounds: '#full-container .editor',
                modules: {
                    formula: true,
                    syntax: true,
                    toolbar: [
                        [{
                                font: []
                            },
                            {
                                size: []
                            }
                        ],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                                color: []
                            },
                            {
                                background: []
                            }
                        ],
                        [{
                                script: 'super'
                            },
                            {
                                script: 'sub'
                            }
                        ],
                        [{
                                header: ['1', '2', '3', '4', '5', '6', '']
                            },
                            'blockquote',
                            'code-block'
                        ],
                        [{
                                list: 'ordered'
                            },
                            {
                                list: 'bullet'
                            },
                            {
                                indent: '-1'
                            },
                            {
                                indent: '+1'
                            }
                        ],
                        [
                            'direction',
                            {
                                align: []
                            }
                        ],
                        ['link', 'image', 'video', 'formula'],
                        ['clean']
                    ]
                },
                theme: 'snow'
            });

            editorContent.on('text-change', function(delta, oldDelta, source) {
                $('#content').val(editorContent.root.innerHTML);
            });

            let content = $('#content').val();
            if (content != '') {
                editorContent.root.innerHTML = content;
            }
        })(window, document, jQuery);

        const getStatus = async () => {
            let urlGetStatus = $('#urlGetStatus').val();
            //console.log( title );
            await $.ajax({
                url: urlGetStatus,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    //console.log( response );
                    let oldStatus = $('#oldStatus').val();
                    $('#status').empty();
                    Object.entries(response).forEach(([key, value]) => {
                        let selected = '';
                        if (oldStatus == '') {
                            if (value == 'Borrador') {
                                selected = 'selected';
                            }
                        } else {
                            if (oldStatus === key) {
                                selected = 'selected';
                            }
                        }
                        let option = `<option value="${key}" ${selected} >${value}</option>`;
                        $('#status').append(option);
                    });
                },
                error: function(response) {
                    console.log(response);
                },
            });
        }

        const getThemes = async () => {
            let urlGetThemes = $('#urlGetThemes').val();
            //console.log( title );
            await $.ajax({
                url: urlGetThemes,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    //console.log( response );
                    let oldThemes = $('#oldThemes').val();
                    oldThemes = oldThemes.split(',');
                    $('#themes').empty();
                    Object.entries(response).forEach(([key, value]) => {
                        let selected = '';
                        if (oldThemes.includes(key)) {
                            selected = 'selected';
                        }
                        let option = `<option value="${key}" ${selected} >${value}</option>`;
                        $('#themes').append(option);
                    });
                },
                error: function(response) {
                    console.log(response);
                },
            });
        }

        const getPostsTypes = async () => {
            let urlGetPostsTypes = $('#urlGetPostsTypes').val();
            //console.log( title );
            await $.ajax({
                url: urlGetPostsTypes,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    //console.log( response );
                    $('#container-post-type').empty();
                    Object.entries(response).forEach(([key, value]) => {
                        let old = $('#oldPostType').val();
                        //console.log( oldLevel );
                        let checked = '';
                        if (old == key) {
                            checked = 'checked';
                        } else if (old === 0 || old === '') {
                            if (key == 1) {
                                checked = 'checked';
                            }
                        }
                        let option = `<div class="form-check form-check-inline">
                        <input
                            class="form-check-input post-type"
                            type="radio"
                            name="post_type"
                            id="${value.toLowerCase()}"
                            value="${key}"
                            ${checked}
                            />
                            <label class="form-check-label" for="${value.toLowerCase()}">${value}</label>
                        </div>`;
                        $('#container-post-type').append(option);
                    });

                    const videoOptions = async () => {
                        let postType = $('input[name="post_type"]:checked').val();
                        if (postType != 1) {
                            $('#row-transcription').css({
                                'display': 'none'
                            });
                            $('#row-videos').css({
                                'display': 'none'
                            });
                        } else {
                            $('#row-transcription').css({
                                'display': 'block'
                            });
                            $('#row-videos').css({
                                'display': 'block'
                            });
                        }
                    }

                    $('input[name="post_type"]').on('change', function() {
                        videoOptions();
                    });

                    videoOptions();

                },
                error: function(response) {
                    console.log(response);
                },
            });
        }

        const getTags = async () => {
            let urlGetTags = $('#urlGetTags').val();
            //console.log( title );
            await $.ajax({
                url: urlGetTags,
                method: 'GET',
                dataType: 'JSON',
                success: function(response) {
                    //console.log( response );
                    let oldTags = $('#oldTags').val();
                    oldTags = oldTags.split(',');
                    $('#tags').empty();
                    Object.entries(response).forEach(([key, value]) => {
                        let selected = '';
                        //console.log( value );
                        if (oldTags.includes(key)) {
                            selected = 'selected';
                        }
                        let option = `<option value="${key}" ${selected} >${value}</option>`;
                        $('#tags').append(option);
                    });
                },
                error: function(response) {
                    console.log(response);
                },
            });
        }

        const init = async () => {
            await getStatus();
            await getThemes();
            await getTags();
            await getPostsTypes();
        }

        init();
    </script>
@endsection
