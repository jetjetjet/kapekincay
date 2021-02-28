<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>Konfigurasi APp</title>
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico"/>
    <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
    <link href="{{ url('/') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ url('/') }}/assets/css/plugins.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/assets/css/elements/alert.css">
    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <link rel="stylesheet" type="text/css" href="{{ url('/') }}/plugins/table/datatable/dt-global_style.css">
    <link href="{{ url('/') }}/plugins/loaders/custom-loader.css" rel="stylesheet" type="text/css" />
</head>
<body class="sidebar-noneoverflow" data-spy="scroll" data-target="#navSection" data-offset="100">
  <div class="main-container" id="container">
    <div id="content" class="main-content">
      <div class="layout-px-spacing">
        <div class="row layout-top-spacing" id="cancel-row">     
          <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            @if(session()->has('error') || $errors->any())
              <div class="alert alert-light-danger mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close" data-dismiss="alert"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button>
                <b>Kesalahan!</b>
                <ul>
                @if($errors->any())
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                @else
                  @foreach (session('error') as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                @endif
                </ul>
              </div> 
            @endif
            @yield('content-body')
          </div>
        </div>
      </div>
    </div>
  </div>
    
    
    
  <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
  <script src="{{ url('/') }}/assets/js/libs/jquery-3.1.1.min.js"></script>
  <script src="{{ url('/') }}/bootstrap/js/popper.min.js"></script>
  <script src="{{ url('/') }}/bootstrap/js/bootstrap.min.js"></script>
  <script src="{{ url('/') }}/assets/js/custom.js"></script>
  <script src="{{ url('/') }}/assets/js/forms/bootstrap_validation/bs_validation_script.js"></script>
  @yield('js-body')
</body>
</html>