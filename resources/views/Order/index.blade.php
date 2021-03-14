@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>User</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">User</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
      <h3>Pesanan Bungkus</h3>
      <table id="gridtakeaway" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No.Invoice</th>
            <th>Tipe pesanan</th>
            <th>tanggal</th>
            <th>total</th>
            <th>status</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
          <th>No.Invoice</th>
            <th>Tipe Pesanan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
<br>
<hr>
<br>
    <div class="table-responsive mb-4 mt-4">
      <h3>Makan Ditempat</h3>
      <table id="griddinein" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>No.Invoice</th>
            <th>No.meja</th>
            <th>Tipe pesanan</th>
            <th>tanggal</th>
            <th>total</th>
            <th>status</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
          <th>No.Invoice</th>
            <th>No.Meja</th>
            <th>Tipe Pesanan</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Status</th>
            <th></th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
@endsection

@section('js-table')
  <script>
    $(document).ready(function (){
      let grid = $('#gridtakeaway').DataTable({
        ajax: {
          url: "{{ url('order/index/grid/takeaway') }}",
          dataSrc: ''
      },
        dom: '<"row"<"col-md-12"<"row"<"col-md-1"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "Tambah Baru",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/order') }}";
              }
            }]
        },
        "processing": false,
        "serverSide": false,
        "oLanguage": {
          "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 8,
        columns: [
          { 
            data: 'orderinvoice',
            searchText: true
          },
          { 
              data: 'ordertypetext',
              searchText: true
          },
          { 
              data: 'orderdate',
              searchText: true
          },
          { 
            data: null,
            render: function(data, type, full, meta){
            return formatter.format(data.orderprice);
            }
          },
          { 
              data: 'orderstatuscase',
              searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              if(data.orderstatuscase == "Lunas"){
                return '<a href="#" title="Detail" class="gridDetail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search p-1 br-6 mb-1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></a>';
              }else{
                return '^'
              }
           }
          }
        ]
      });

      $('#gridtakeaway').on('click', 'a.gridDetail', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/order/detail') . '/' }}"+ rowData.id;
      });



    let grid2 = $('#griddinein').DataTable({
        ajax: {
          url: "{{ url('order/index/grid/dinein') }}",
          dataSrc: ''
      },
        dom: '<"row"<"col-md-12"<"row"<"col-md-1"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "Tambah Baru",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/order') }}";
              }
            }]
        },
        "processing": false,
        "serverSide": false,
        "oLanguage": {
          "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
          "sInfo": "Halaman _PAGE_ dari _PAGES_",
          "sSearch": '<i data-feather="search"></i>',
          "sSearchPlaceholder": "Cari...",
          "sLengthMenu": "Hasil :  _MENU_",
        },
        "stripeClasses": [],
        "lengthMenu": [10, 20, 50],
        "pageLength": 8,
        columns: [
          { 
            data: 'orderinvoice',
            searchText: true
          },
          { 
              data: 'orderboardid',
              searchText: true
          },
          { 
              data: 'ordertypetext',
              searchText: true
          },
          { 
              data: 'orderdate',
              searchText: true
          },
          { 
            data: null,
            render: function(data, type, full, meta){
            return formatter.format(data.orderprice);
            }
          },
          { 
              data: 'orderstatuscase',
              searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              if(data.orderstatuscase == "Lunas"){
                return '<a href="#" title="Detail" class="gridDetail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search p-1 br-6 mb-1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></a>';
              }else{
                return '^'
              }
           }
          }
        ]
      });


      $('#griddinein').on('click', 'a.gridDetail', function (e) {
        e.preventDefault();
        const rowData = grid2.row($(this).closest('tr')).data();

        window.location = "{{ url('/order/detail') . '/' }}"+ rowData.id;
      });

  });
  </script>
@endsection
