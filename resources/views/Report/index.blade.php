@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Laporan Transaksi</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Laporan</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Laporan Transaksi</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <form id="formsub" class="needs-validation" method="get" novalidate action="{{ url('/laporan/') }}">
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
          <h4>Status Order</h4>
          <select id='status' class="form-control" name="status">
            <option value="Semua">Semua</option>
              <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : ''}}>Lunas</option>
              <option value="Diproses" {{ request('status') == 'Diproses' ? 'selected' : ''}}>Diproses</option>
              <option value="VOIDED" {{ request('status') == 'VOIDED' ? 'selected' : ''}}>Dibatalkan</option>
          </select>
        </div> 
        <div class="col-md-6 mb-1">
          <h4>Status Pengeluaran</h4>
          <select id='status' class="form-control" name="statusEx">
            <option value="Semua">Semua</option>
              <option value="1" {{ request('statusEx') == '1' ? 'selected' : ''}}>Selesai</option>
              <option value="0" {{ request('statusEx') == '0' ? 'selected' : ''}}>Draft</option>
          </select>
        </div> 
        <div class="col-md-12 mb-1">
          <h4>Karyawan</h4>
          <select id='user' class="form-control" name="user">
            <option value="Semua">Semua</option>
            @foreach($user as $u)
              <option value="{{$u->id}}" {{ request('user') == $u->id ? 'selected' : ''}}>{{$u->username}}</option>
            @endforeach
          </select>
          <input type="hidden" id='domkar' name="reqkar">
        </div>
        <div class="col-md-12">
          <div class="float-right mb-3">
            @if($total[0]['total'] != 0 || $total[1]['totalex'] != 0)
            <button id="print" style="background-color:#1D6F42; color:white" class="btn mt-3">Export</button>
            @endif
            <button class="btn btn-primary mt-3" id="sub" type="submit">Cari</button>
          </div>
        </div>
      </div>
    </form>
    @if($total[0]['total'] != 0 || $total[1]['totalex'] != 0)
    <div class="row">
      <div class="col-md-12">
        <hr>
        <h3 style="color:#1b55e2">Hasil Pencarian</h3>
      </div>
      <div class="table-responsive mb-4 mt-4 col-md-12">
        <table id="gridod" class="table table-hover" style="width:100%">
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
              <td>{{$key + 1}}</td>
              <td>{{$row['tanggal']}}</td>
              <td><a href="{{url('/order/detail')}}/{{$row['id']}}">{{$row['orderinvoice']}}</a></td>
              <td>{{$row['ordertypetext']}}</td>
              @if(isset($row['orderdiscountprice']))
                <td>{{number_format($row['price'])}}<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign p-1 br-6 mb-1"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path><title>Potongan Harga {{number_format($row->orderdiscountprice)}}</title></svg></td>
              @else 
                <td>{{number_format($row['price'])}}</td>
              @endif
              <td>{{$row['orderstatuscase']}}</td>
              <td>{{$row['username']}}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>No. Invoice</th>
              <th>Tipe Pesanan</th>
              <th>Harga</th>
              <th>Status</th>
              <th>Karyawan</th>
            </tr>
          </tfoot>
        </table>
        <hr>
      </div>
      <div class="table-responsive mb-4 mt-4 col-md-12">
        <table id="gridex" class="table table-hover" style="width:100%">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>Jumlah</th>
              <th>Detail</th>
              <th>Karyawan</th>
              <th>Status</th>
              <th>Diselesaikan oleh</th>
              <th>Tanggal Diselesaikan</th>
            </tr>
          </thead>
          <tbody>
            @foreach($data->ex as $key=>$row)
            <tr>
              <td>{{$key + 1}}</td>
              <td><a href="{{url('/pengeluaran/detail')}}/{{$row['id']}}">{{$row['expensename']}}</a></td>
              <td>{{$row['tanggal']}}</td>
              <td>{{number_format($row['expenseprice'])}}</td>
              <td style="width:17%">{{$row['expensedetail']}}</td>
              <td>{{$row['create']}}</td>
              <td>{{$row['status']}}</td>
              <td>{{$row['execute']}}</td>
              <td>{{$row['tanggalend']}}</td>
            </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Tanggal</th>
              <th>Jumlah</th>
              <th>Detail</th>
              <th>Karyawan</th>
              <th>Status</th>
              <th>Diselesaikan oleh</th>
              <th>Tanggal Diselesaikan</th>
            </tr>
          </tfoot>
        </table>
      </div> 
      <div class="row col-md-12">
				<div class="col-md-12 text-right">
        	<hr>
					<h2>Periode <b>{{request('startdate')}} - {{request('enddate')}}</b></h2>
				</div>    
        <div class="col-md-10 text-right">
          <h3>Total Pemasukan :</h3>
          <h3>Total Pengeluaran :</h3>
          <br>
          <h3>Total Akhir :</h3>
        </div>
        <div class="col-md-2 text-right">       
          <h3><b>{{number_format($total[0]['total'])}}</b></h3>
          <h3><b>{{number_format($total[1]['totalex'])}}</b></h3>
          <br>
          <h3><b>{{number_format($total[0]['total']-$total[1]['totalex'])}}</b></h3>
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
    $(document).ready(function (){

      $('#print').click(function (e){
        $('[name=print]').remove();
        $('form')
          .append('<input type="hidden" name="print" value="1" />')
          .attr('target', '_blank')
          .submit();
      });

      $('[type=submit]').click(function (e){
        $('[name=print]').remove();
        $('form').removeAttr('target');
      });
      
      flatpickr($('#start'), {
        dateFormat: "d-m-Y",
        altinput: true,
        altformat: "Y-m-d",
        maxDate: "today",
        defaultDate: "{{ request('startdate') != null ? request('startdate') : Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}",
        onChange: function (selectedDates, dateStr, instance) {
          endPicker.set("minDate", dateStr);
          $('#end').removeAttr('disabled')
        }
      });

      let endPicker = flatpickr($('#end'), {
        dateFormat: "d-m-Y",
        altinput: true,
        altformat: "Y-m-d",
        minDate: "{{request('startdate') != null ? request('startdate') : Carbon\Carbon::now()->startOfMonth()->format('d-m-Y') }}",
        maxDate: "{{Carbon\Carbon::now()->endOfMonth()->format('d-m-Y')}}",
        defaultDate: "{{ request('enddate') != null ? request('enddate') : Carbon\Carbon::now()->endOfMonth()->format('d-m-Y')}}"
      });

      $('#formsub').on('submit', function(){
        let us = $( "#user option:selected" ).text();
        $('#domkar').val(us)
      })

      $('#gridod').DataTable( {
        dom: '<"row"<"col-md-12"rt> <"col-md-12"<"row"<"col-md-4"i><"col-md-8"p>>> >',
        "oLanguage": {
          "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
        },
        "pageLength": 20
      });

      $('#gridex').DataTable( {
        dom: '<"row"<"col-md-12"rt> <"col-md-12"<"row"<"col-md-4"i><"col-md-8"p>>> >',
        "oLanguage": {
          "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
        },
        "pageLength": 20
      });

    });
  </script>
@endsection
