@extends('layouts/contentLayoutMaster')

@section('title', 'Media Player')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset('vendors/css/extensions/plyr.min.css') }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset('css/base/plugins/extensions/ext-component-media-player.css') }}">
@endsection

@section('content')
<!-- Media Player -->
<section id="media-player-wrapper">
  <div class="row">
    <!-- VIDEO -->
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Video</h4>
          <div class="video-player" id="plyr-video-player">
            <iframe src="https://www.youtube.com/embed/bTqVqk7FSmY" allowfullscreen allow="autoplay"></iframe>
          </div>
        </div>
      </div>
    </div>
    <!--/ VIDEO -->

    <!-- AUDIO -->
    <div class="col-12">
      <div class="card">
        <div class="card-body">
          <h4 class="card-title">Audio</h4>
          <audio id="plyr-audio-player" class="audio-player" controls>
            <source
              src="https://cdn.plyr.io/static/demo/Kishi_Bashi_-_It_All_Began_With_a_Burst.mp3"
              type="audio/mp3"
            />
            <source
              src="https://cdn.plyr.io/static/demo/Kishi_Bashi_-_It_All_Began_With_a_Burst.ogg"
              type="audio/ogg"
            />
          </audio>
        </div>
      </div>
    </div>
    <!--/ AUDIO -->
  </div>
</section>
<!--/ Media Player -->
@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset('vendors/js/extensions/plyr.min.js') }}"></script>
  <script src="{{ asset('vendors/js/extensions/plyr.polyfilled.min.js') }}"></script>
@endsection
@section('page-script')
  <!-- Page js files -->
  <script src="{{ asset('js/scripts/extensions/ext-component-media-player.js') }}"></script>
@endsection
