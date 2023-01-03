
@extends('layouts/contentLayoutMaster')

@section('title', 'Artículos')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset(mix('vendors/css/vendors.min.css')) }}" />
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
<link rel="stylesheet" href="{{asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css'))}}">
@endsection

@section('content')
<!-- Responsive tables start -->
<div class="row" id="table-responsive">
  <div class="col-12">
    <div class="card">
      <div class="card-header border-bottom">
          <div class="head-label">
            <h4 class="mb-0">Artículos</h4>
          </div>
          <div class="dt-action-buttons text-end">

            <div class="dt-buttons d-inline-flex">
              <button class="dt-button create-new btn btn-primary" tabindex="0" aria-controls="DataTables_Table_0" type="button" data-bs-toggle="modal" data-bs-target="#modals-slide-in">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-50 font-small-4">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                  </svg>
                  Nuevo artículo
                </span>
              </button>
            </div>

            </button>
          </div>
      </div>
        <!--Search Form -->
        <div class="card-body mt-2">
          <form id="formSearchArticles" class="dt_adv_search">
            <div class="row g-1 mb-md-1">
              <div class="col-md-2">
                <select class="select2 form-select" id="select2-basic">
                  <option value="">Autor</option>
                  <option value="HI">Hawaii</option>
                </select>
              </div>
              <div class="col-md-2">
                <select class="select2 form-select" id="select2-multiple">
                  <option value="">Temas</option>
                  <option value="HI">Hawaii</option>
                </select>
              </div>
              <div class="col-md-2">
                <select class="select2 form-select" id="select2-multiple">
                  <option value="">Status</option>
                  <option value="HI">Hawaii</option>
                </select>
              </div>
              <div class="col-md-6">
                <div class="input-group input-group-merge mb-2">
                  <span class="input-group-text" id="basic-addon-search"><i data-feather="search"></i></span>
                  <input 
                    id="searchCriteriaUsers" 
                    name="searchCriteriaUsers" 
                    type="text"
                    class="form-control"
                    placeholder="Buscar usuario"
                    aria-label="Search..."
                    aria-describedby="basic-addon-search"
                  />
                </div>
              </div>
            </div>
          </form>
        </div>
        <hr class="my-0" />

        <div class="contentTableUsers">

            <table class="table">
              <thead>
                <tr>
                  <th> </th>
                  <th>Titular</th>
                  <th>Autor</th>
                  <th>Temas</th>
                  <th>Publicación</th>
                  <th>Status</th>
                  <th> </th>
                </tr>
              </thead>
              <tbody>
                @foreach($articles as $article)
                <tr>
                  <td><span class="badge rounded-pill badge-light-secondary me-1"><strong>{{ $article->id }}</strong></span></td>
                  <td>{{ $article->title }}</td>
                  <td>{{ $article->author->name }}</td>

                  <td>
                    <ul>
                    @foreach($article->articletag as $tag)
                    <li type="circle">{{ $tag['tag']->name }}</li>
                    @endforeach
                    </ul>
                  </td>

                  <td>{{ date("d/m/Y", strtotime($article->created_at)) }}</td>

                  @if($article->status->code == 'ARC')
                  <td><span class="badge rounded-pill badge-light-danger me-1">{{ $article->status->name }}</span></td>
                  @endif

                  @if($article->status->code == 'BOR')
                  <td><span class="badge rounded-pill badge-light-warning me-1">{{ $article->status->name }}</span></td>
                  @endif

                  @if($article->status->code == 'PUB')
                  <td><span class="badge rounded-pill badge-light-success me-1">{{ $article->status->name }}</span></td>
                  @endif

                  <td>
                    <div class="dropdown">
                      <button type="button" class="btn btn-sm dropdown-toggle hide-arrow py-0" data-bs-toggle="dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-vertical"><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="5" r="1"></circle><circle cx="12" cy="19" r="1"></circle></svg>
                      </button>
                      <div class="dropdown-menu dropdown-menu-end">
                        <a value="{{ $article->id }}" id="editArticles" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editArticleModal">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2 me-50"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                          <span>Editar</span>
                        </a>
                        <a value="{{ $article->id }}" id="deteleArticles" class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deteleArticleModal">
                          <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash me-50"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
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

            <div id="paginatorTableArticles" class="d-flex justify-content-between p-2">

            Mostrando {{ $articles->firstItem() }} - {{ $articles->lastItem() }} de un total de {{ $articles->total() }} registros

            {{ $articles->links() }}

            </div>             
          
        </div>
    </div>
  </div>
</div>
<!-- Responsive tables end -->
@endsection
<script src="../../../../vendors/js/jquery/jquery.js"></script>
<script src="../../../../vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="../../../../vendors/js/forms/select/select2.full.min.js"></script>
<script>
(function (window, document, $) {
  'use strict';

$("#select2-multiple").select2();


  var select = $('.select2'),
    selectIcons = $('.select2-icons'),
    maxLength = $('.max-length'),
    hideSearch = $('.hide-search'),
    selectArray = $('.select2-data-array'),
    selectAjax = $('.select2-data-ajax'),
    selectLg = $('.select2-size-lg'),
    selectSm = $('.select2-size-sm'),
    selectInModal = $('.select2InModal');

  select.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
      // the following code is used to disable x-scrollbar when click in select input and
      // take 100% width in responsive also
      dropdownAutoWidth: true,
      width: '100%',
      dropdownParent: $this.parent()
    });
  });

  // Select With Icon
  selectIcons.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
      dropdownAutoWidth: true,
      width: '100%',
      minimumResultsForSearch: Infinity,
      dropdownParent: $this.parent(),
      templateResult: iconFormat,
      templateSelection: iconFormat,
      escapeMarkup: function (es) {
        return es;
      }
    });
  });

  // Format icon
  function iconFormat(icon) {
    var originalOption = icon.element;
    if (!icon.id) {
      return icon.text;
    }

    var $icon = feather.icons[$(icon.element).data('icon')].toSvg() + icon.text;

    return $icon;
  }

  // Limiting the number of selections
  maxLength.wrap('<div class="position-relative"></div>').select2({
    dropdownAutoWidth: true,
    width: '100%',
    maximumSelectionLength: 2,
    dropdownParent: maxLength.parent(),
    placeholder: 'Select maximum 2 items'
  });

  // Hide Search Box
  hideSearch.select2({
    placeholder: 'Select an option',
    minimumResultsForSearch: Infinity
  });

  // Loading array data
  var data = [
    { id: 0, text: 'enhancement' },
    { id: 1, text: 'bug' },
    { id: 2, text: 'duplicate' },
    { id: 3, text: 'invalid' },
    { id: 4, text: 'wontfix' }
  ];

  selectArray.wrap('<div class="position-relative"></div>').select2({
    dropdownAutoWidth: true,
    dropdownParent: selectArray.parent(),
    width: '100%',
    data: data
  });

  // Loading remote data
  selectAjax.wrap('<div class="position-relative"></div>').select2({
    dropdownAutoWidth: true,
    dropdownParent: selectAjax.parent(),
    width: '100%',
    ajax: {
      url: 'https://api.github.com/search/repositories',
      dataType: 'json',
      delay: 250,
      data: function (params) {
        return {
          q: params.term, // search term
          page: params.page
        };
      },
      processResults: function (data, params) {
        // parse the results into the format expected by Select2
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data, except to indicate that infinite
        // scrolling can be used
        params.page = params.page || 1;

        return {
          results: data.items,
          pagination: {
            more: params.page * 30 < data.total_count
          }
        };
      },
      cache: true
    },
    placeholder: 'Search for a repository',
    escapeMarkup: function (markup) {
      return markup;
    }, // let our custom formatter work
    minimumInputLength: 1,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection
  });

  function formatRepo(repo) {
    if (repo.loading) return repo.text;

    var markup =
      "<div class='select2-result-repository clearfix'>" +
      "<div class='select2-result-repository__avatar'><img src='" +
      repo.owner.avatar_url +
      "' /></div>" +
      "<div class='select2-result-repository__meta'>" +
      "<div class='select2-result-repository__title'>" +
      repo.full_name +
      '</div>';

    if (repo.description) {
      markup += "<div class='select2-result-repository__description'>" + repo.description + '</div>';
    }

    markup +=
      "<div class='select2-result-repository__statistics'>" +
      "<div class='select2-result-repository__forks'>" +
      feather.icons['share-2'].toSvg({ class: 'me-50' }) +
      repo.forks_count +
      ' Forks</div>' +
      "<div class='select2-result-repository__stargazers'>" +
      feather.icons['star'].toSvg({ class: 'me-50' }) +
      repo.stargazers_count +
      ' Stars</div>' +
      "<div class='select2-result-repository__watchers'>" +
      feather.icons['eye'].toSvg({ class: 'me-50' }) +
      repo.watchers_count +
      ' Watchers</div>' +
      '</div>' +
      '</div></div>';

    return markup;
  }

  function formatRepoSelection(repo) {
    return repo.full_name || repo.text;
  }

  // Sizing options

  // Large
  selectLg.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
      dropdownAutoWidth: true,
      dropdownParent: $this.parent(),
      width: '100%',
      containerCssClass: 'select-lg'
    });
  });

  // Small
  selectSm.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this.select2({
      dropdownAutoWidth: true,
      dropdownParent: $this.parent(),
      width: '100%',
      containerCssClass: 'select-sm'
    });
  });

  $('#select2InModal').on('shown.bs.modal', function () {
    selectInModal.select2({
      placeholder: 'Select a state'
    });
  });
})(window, document, jQuery);
</script>