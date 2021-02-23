@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Laporan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Laporan</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <form class="needs-validation" method="get" novalidate action="{{ url('/laporan/') }}">
      <div class="form-row">     
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
        <div class="col-md-4 mb-1">
          <h4>Tanggal Awal</h4>
          <input id="start" value="{{request('startdate')}}" name="startdate" class="form-control flatpickr flatpickr-input date">
        </div>
        <div class="col-md-4 mb-1">
          <h4>Tanggal Akhir</h4>
          <input id="end" value="{{request('enddate')}}" name="enddate" class="form-control flatpickr flatpickr-input date">
        </div>  
        <div class="col-md-4 mb-1"></div>
        <div class="col-md-4 mb-1">
          <h4>Karyawan</h4>
          <input name="user" class="form-control" type="hidden">
          <select class="form-control select2" name="user" multiple="multiple">
            @foreach($user as $key=>$u)
            <?php
            $userActive = request('user');
            $selectedUser = $userActive ? ' selected' : null;
            ?>
              <option value="{{$u->username}}" {!! $selectedUser !!}>{{$u->username}}</option>
            @endforeach
          </select>
        </div> 
      </div>
      <div class="float-right mb-3">
        <button class="btn btn-primary mt-2" id="sub" type="submit">Cari</button>
      </div>
    </form>
  @if($data->sub['total'] != 0)
    <div class="table-responsive mb-4 mt-4">
      <hr>
      <h3 style="color:#1b55e2">Laporan</h3>
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>No. Invoice</th>
            <th>Tipe Pesanan</th>
            <th>Harga</th>
            <th>Status</th>
            <th>Karyawan</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $key=>$row)
          <tr>
            <td>{{$row['id']}}</td>
            <td>{{$row['tanggal']}}</td>
            <td>{{$row['orderinvoice']}}</td>
            <td>{{$row['ordertypetext']}}</td>
            <td>{{$row['orderprice']}}</td>
            <td>{{$row['orderstatuscase']}}</td>
            <td>{{$row['username']}}</td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th colspan="6" class="text-right"><h3>Total : </h3></th>
            <th><h3><b>{{number_format($data->sub['total'])}}</b></h3></th>
          </tr>
        </tfoot>
      </table>
      </div>      
    </div>
    <div class="row fixed-bottom">
      <div class="col-sm-12">
        <div class="widget-content widget-content-area float-right" style="padding:10px">
          <button class="btn btn-primary mt-2" id="pdf" type="submit">Export Ke PDF</button>
        </div>
      </div>
    </div>
    @else
    <div class="table-responsive mb-4 mt-4">
    <div style="text-align:center;">
      <h3>Data Kosong</h3>
    </div>
  </div>
    @endif
  </div>
@endsection

@section('js-form')
  <script>
    var chg = function(){
      var str = $('#start').val()
      var end = $('#end').val()
      flatpickr($('#end'), {
      minDate:str
    });
    flatpickr($('#start'), {
      maxDate:end
    });
    }


    $(document).ready(function (){

      $('.select2').select2({
      tags: false,
      placeholder: "{{ request('user') != null ? request('user') : 'Pilih' }}",
      searchInputPlaceholder: 'Search options',
      maximumSelectionLength: 1
    });
      
    flatpickr($('#end'), {
      dateFormat: "d-m-Y",
      altinput: true,
      altformat: "Y-m-d",
      maxDate: new Date().fp_incr(1),
      defaultDate: "{{ request('enddate') != null ? request('enddate') : Carbon\Carbon::tomorrow()->format('d-m-Y')}}"
    });
    flatpickr($('#start'), {
      dateFormat: "d-m-Y",
      altinput: true,
      altformat: "Y-m-d",
      maxDate: "today",
      defaultDate: "{{ request('startdate') != null ? request('startdate') : 'today' }}"
    });
// $('#start').change(function(){
// chg()
// })
// $('#end').change(function(){
// chg()
// })
    
    });
  </script>
@endsection
