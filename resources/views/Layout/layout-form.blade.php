@extends('Layout.index')

@section('css-body')
<link href="{{ url('/') }}/assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />

  <link href="{{ url('/') }}/assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
  <link href="{{ url('/') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
  <link href="{{ url('/') }}/plugins/flatpickr/flatpickr.css" rel="stylesheet" type="text/css">
  <link href="{{ url('/') }}/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
  <link href="{{ url('/') }}/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/select2/select2.min.css">
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/forms/theme-checkbox-radio.css">
  <link href="{{ url('/') }}/plugins/file-upload/file-upload-with-preview.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/forms/switches.css">

  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/editors/quill/quill.snow.css">
  <link href="{{ url('/') }}/assets/css/apps/todolist.css" rel="stylesheet" type="text/css" />
  <link href="{{ url('/') }}/assets/css/tables/table-basic.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/editors/quill/quill.snow.css">
  <link href="{{ url('/') }}/assets/css/apps/todolist.css" rel="stylesheet" type="text/css" />

  <style>
    /* SELECT2 MODAL */
    .select2-close-mask{
      z-index: 2099;
    }
    .select2-dropdown{
      z-index: 3051;
    }

    .container .jumbotron {
      box-shadow: 0 0 50px #3F0C1F;
      border: 2px solid #3F0C1F;
    }

    /* MENU CATEGORIES PAGE */
    .category-tile { 
      position: relative;
      border: 1px solid #3F0C1F;
      overflow: hidden;
      width: 120px; /*automatically center image: give width, and margin left/right to auto */
      height: 120px;
      margin: 5px 5px 5px;
    }
    .category-tile span {
      position: absolute;
      bottom: 0;
      right: 0;
      width: 100%;
      text-align: center;
      text-transform: uppercase;
      background-color: #000;
      color: #fff;
      opacity: .8;
    }

    .imgrespo {
  height: 30vw;
  width: auto;
  min-height: 55px;
  max-height: 295px;
}

/* END MENU CATEGORIES PAGE */
  </style>
@endsection

@section('content-breadcumb')
  @yield('breadcumb')
@endsection

@section('content-body')
  @yield('content-form')
@endsection


@section('js-body')
  <script src="{{ url('/') }}/assets/js/scrollspyNav.js"></script>
  <script src="{{ url('/') }}/plugins/flatpickr/flatpickr.js"></script>
  <script src="{{ url('/') }}/plugins/flatpickr/custom-flatpickr.js"></script>
  <script src="{{ url('/') }}/plugins/select2/select2.min.js"></script>
  <script src="{{ url('/') }}/plugins/select2/custom-select2.js"></script>
  <script src="{{ url('/') }}/plugins/input-mask/jquery.inputmask.bundle.min.js"></script>
  <script src="{{ url('/') }}/plugins/input-mask/input-mask.js"></script>
  <script src="{{ url('/') }}/plugins/file-upload/file-upload-with-preview.min.js"></script>

  <script src="{{ url('/') }}/assets/js/ie11fix/fn.fix-padStart.js"></script>
  <script src="{{ url('/') }}/plugins/editors/quill/quill.js"></script>
  <script src="{{ url('/') }}/assets/js/apps/todoList.js"></script>
  <script>
    numberMask();
  </script>
  @yield('js-form')
@endsection