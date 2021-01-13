@extends('Layout.index')

@section('css-body')
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/datatables.css">
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/custom_dt_html5.css">
  <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/dt-global_style.css">
@endsection

@section('content-breadcumb')
  @yield('breadcumb')
@endsection

@section('content-body')
  @yield('content-table')
@endsection


@section('js-body')
  <script src="{{ url('/') }}/plugins/table/datatable/datatables.js"></script>
  <script src="{{ url('/') }}/plugins/table/datatable/button-ext/dataTables.buttons.min.js"></script>    
  <script src="{{ url('/') }}/plugins/table/datatable/button-ext/buttons.html5.min.js"></script>
  @yield('js-table')
@endsection