@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Laporan Transaksi</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Laporan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Laporan Shift</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <form class="needs-validation" method="get" novalidate action="{{ url('/laporan-shift') }}">
      <div class="form-row">     
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
        <div class="col-md-6 mb-1">
          <h4>Tanggal Awal</h4>
          <input id="start" value="{{request('startdate')}}" name="startdate" class="form-control flatpickr flatpickr-input date">
        </div>
        <div class="col-md-6 mb-1">
          <h4>Tanggal Akhir</h4>
          <input id="end" value="{{request('enddate')}}" name="enddate" class="form-control flatpickr flatpickr-input date">
        </div>  
        <div class="col-md-6 mb-1">
          <h4>Karyawan</h4>
          <select id='user' class="form-control select2" name="user">
            <option value="ALL">Semua Karyawan</option>
            @foreach($user as $u)
              <option value="{{$u->id}}" {{ request('user') == $u->id ? 'selected' : ''}}>{{$u->username}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-6 mb-1">
          <h4>Status Pesanan</h4>
          <select id='status' class="form-control" name="status">
            <option value="ALL">Semua</option>
            <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : ''}}>Lunas</option>
            <option value="INPROG" {{ request('status') == 'Diproses' ? 'selected' : ''}}>Diproses</option>
            <option value="VOIDED" {{ request('status') == 'VOIDED' ? 'selected' : ''}}>Dibatalkan</option>
          </select>
        </div> 
      </div>
      <div class="float-right mb-3">
        <button class="btn btn-primary mt-2" id="sub" type="submit">Cari</button>
      </div>
    </form>
  @if($data)
    <div class="table-responsive mb-4 mt-4">
      <hr>
      <h3 style="color:#1b55e2">Hasil Pencarian</h3>
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Karyawan</th>
            <th>Buka Shift</th>
            <th>Tutup Shift</th>
            <th>Selisih</th>
            <th>Total Pesanan</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1 ?>
          @foreach($data->data as $key=>$row)
          <tr>
            <td>{{$no++}}</td>
            <td>{{\carbon\carbon::parse($row->shiftcreatedat)->format('d-m-Y') }}</td>
            <td>{{$row->username}}</td>
            <td>Rp{{number_format($row->totalstart)}} <br /> (Kertas: {{number_format($row->kertasawal)}} Koin: {{number_format($row->koinawal)}})</td>
            <td>Rp{{number_format($row->totalakhir)}} <br /> (Kertas: {{number_format($row->kertasakhir)}} Koin: {{number_format($row->koinakhir)}})</td>
            <td>Rp{{number_format($row->selisih)}}</td>
            <td>Rp{{number_format($row->totalorder)}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
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
      $('#user').select2({
        tags: false,
        searchInputPlaceholder: 'Search options',
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

      $('#grid').DataTable( {
        dom: '<"row"<"col-md-12"<"row"<"col-md-6"B> > ><"col-md-12"rt> >',
        buttons: {
            buttons: [
              { extend: 'copy', text:'Salin', className: 'btn', footer:'true' },
              { 
                extend: 'print', 
                className: 'btn', 
                title:"",
                text:'PDF/Cetak',
                footer:'true',
                customize: function ( win ) {
                  $(win.document.body)
                    .prepend(
                      "<br><h2><b>{{session('cafeName')}}</b></h2><hr>"+
                      "<h2 style='color:#1b55e2'>Laporan Transaksi</h2>"+
                      "<div class='form-row'>"+
                      "<div class='col-md-6 float-left'><h4>Karyawan : <b>{{request('user')}}</b></h4></div>"+
                      "<div class='col-md-6'><h4 class='text-right'>{{request('startdate')}}/{{request('enddate')}}</h4></div>"+
                      "</div>"
                    );
                }
              },
            ]
          },
        paging: false
      });
    
    });
  </script>
@endsection
