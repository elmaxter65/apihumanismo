
@extends('layouts/contentLayoutMaster')

@section('title', 'Dashboard Analytics')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset('vendors/css/charts/apexcharts.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/extensions/toastr.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css') }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset('css/base/plugins/charts/chart-apex.css') }}">
  <link rel="stylesheet" href="{{ asset('css/base/plugins/extensions/ext-component-toastr.css') }}">
  <link rel="stylesheet" href="{{ asset('css/base/pages/app-invoice-list.css') }}">
  @endsection

@section('content')
<!-- Dashboard Analytics Start -->
<section id="dashboard-analytics">
  <div class="row match-height">


  </div>

  <div class="row match-height">

  </div>

  <div class="row match-height">

  </div>

  <!-- List DataTable -->
  <div class="row">

  </div>
  <!--/ List DataTable -->
</section>
<!-- Dashboard Analytics end -->
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset('vendors/js/charts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('vendors/js/extensions/toastr.min.js') }}"></script>
  <script src="{{ asset('vendors/js/extensions/moment.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('vendors/js/tables/datatable/responsive.bootstrap5.js') }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  {{-- <script src="{{ asset('js/scripts/pages/dashboard-analytics.js') }}"></script> --}}
  <script src="{{ asset('js/scripts/pages/app-invoice-list.js') }}"></script>
@endsection
