@extends('Layout.index')

@section('css-body')

  <link href="{{ url('/') }}/assets/css/apps/notes.css" rel="stylesheet" type="text/css" />
@endsection

@section('content-breadcumb')
  @yield('breadcumb')
@endsection

@section('content-body')
  @yield('content-table')
@endsection


@section('js-body')

  <script src="{{ url('/') }}/assets/js/apps/notes.js"></script>
  @yield('js-table')
@endsection