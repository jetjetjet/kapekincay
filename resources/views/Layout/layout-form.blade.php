@extends('Layout.index')

@section('css-body')
  <link href="{{ url('/') }}/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
  <link href="{{ url('/') }}/assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css" />
  <link href="{{ url('/') }}/assets/css/scrollspyNav.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/select2/select2.min.css">

  <style>
    .toggle-switch {
      display: flex;
      margin-right: 7px;
    }

    .switch {
      position: relative;
      display: inline-block;
      width: 35px;
      height: 18px;
    }
    /* Hide default HTML checkbox */
    .switch input {display:none;}
    /* The slider */
    .switch .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ebedf2;
      -webkit-transition: .4s;
      transition: .4s;
    }
    .switch.s-icons .slider svg {
      position: absolute;
      width: 19px;
      height: 19px;
      top: 4px;
      color: #fff;
      /* fill: rgba(224, 230, 237, 0.9686274509803922); */
    }

    .switch.s-icons .slider svg.feather-sun {
      left: 4px;
      color: #e2a03f;
      fill: #e2a03f;
    }
    .switch.s-icons .slider svg.feather-moon {
      right: 4px;
    }

    .switch .slider:before {
      position: absolute;
      content: "";
      background-color: white;
      -webkit-transition: .4s;
      -ms-transition: .4s;
      transition: .4s;
      height: 14px;
      width: 14px;
      left: 2px;
      bottom: 2px;
      box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.34);
    }
    .switch input:checked + .slider:before {
      -webkit-transform: translateX(17px);
      -ms-transform: translateX(17px);
      transform: translateX(17px)
    }
    /* Rounded Slider Switches */
    .switch .slider.round { border-radius: 34px; }
    .switch .slider.round:before { border-radius: 50%; }

    .switch.s-outline .slider {
      border: 2px solid rgb(129, 149, 238);
      background-color: transparent;
      width: 36px;
      height: 19px;
    }
      
    .switch.s-outline .slider:before {     height: 21px;
      width: 21px; z-index: 1; }
    .switch.s-outline[class*="s-outline-"] .slider:before {
      bottom: 2px;
      left: 3px;
      border: 2px solid #bfc9d4;
      background-color: #bfc9d4;
      color: #ebedf2;
      box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.25);
    }

    .switch.s-icons.s-outline-secondary { color: #5c1ac3; }
    .switch.s-outline-secondary input:checked + .slider { border: 2px solid #e0e6ed; }
    .switch.s-outline-secondary input:checked + .slider:before {
      border: 2px solid #1b55e2;
      background-color: #1b55e2;
      box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.34);
    }
    .switch.s-outline-secondary input:focus + .slider { box-shadow: 0 0 1px #5c1ac3; }

    .switch.s-icons {
      width: 57px;
      height: 30px;
    }
    .switch.s-icons .slider {
      width: 64px;
      height: 30px;
      background: #3b3f5c;
      border-color: #3b3f5c;
    }
    .switch.s-outline-secondary input:checked + .slider { background: #e0e6ed; }

    .switch.s-icons input:checked + .slider:before {
      -webkit-transform: translateX(34px);
      -ms-transform: translateX(34px);
      transform: translateX(34px);
    }
      
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
  <script src="{{ url('/') }}/plugins/select2/select2.min.js"></script>
  <script src="{{ url('/') }}/plugins/select2/custom-select2.js"></script>
  <script src="{{ url('/') }}/plugins/input-mask/jquery.inputmask.bundle.min.js"></script>
  <script src="{{ url('/') }}/plugins/input-mask/input-mask.js"></script>
  @yield('js-form')
@endsection