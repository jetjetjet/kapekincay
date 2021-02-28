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
          "oPaginate": { "sPrevious": '<i data-feather="arrow-left"></i>', "sNext": '<i data-feather="arrow-right"></i>' },
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
              return '<a href="#" title="Delete" class="gridDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>' +
              '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>'+ 
              '<a href="#" title="Detail" class="gridDetail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search p-1 br-6 mb-1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></a>'+ 
              '<a href="#" title="Batalkan" class="gridVoid"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon p-1 br-6 mb-1"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a>';
            }
          }
        ]
      });
      $('#gridtakeaway').on('click', 'a.gridEdit', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/order') . '/' }}"+ rowData.id;
      });

      $('#gridtakeaway').on('click', 'a.gridDetail', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/order/detail') . '/' }}"+ rowData.id;
      });

      $('#gridtakeaway').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('order/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Pesanan';
        const pesan = 'Apakah anda yakin ingin menghapus pesanan ini?'
        console.log(rowData, url)
        gridDeleteRow(url, title, pesan, grid);
      });

      $('#gridtakeaway').on('click', 'a.gridVoid', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('order/batal') . '/' }}" + rowData.id;
        const title = 'Batalkan Pesanan';
        const pesan = 'Alasan Pembatalan?'
        const inputan = 'ordervoidreason'
        console.log(rowData, url, inputan)
        gridDeleteInput(url, title, pesan, grid, input);
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
          "oPaginate": { "sPrevious": '<i data-feather="arrow-left"></i>', "sNext": '<i data-feather="arrow-right"></i>' },
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
              return '<a href="#" title="Delete" class="gridDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>' +
              '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>'+ 
              '<a href="#" title="Detail" class="gridDetail"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search p-1 br-6 mb-1"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg></a>'+ 
              '<a href="#" title="Batalkan" class="gridVoid"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-octagon p-1 br-6 mb-1"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a>';
            }
          }
        ]
      });
      $('#griddinein').on('click', 'a.gridEdit', function (e) {
        e.preventDefault();
        const rowData = grid2.row($(this).closest('tr')).data();

        window.location = "{{ url('/order') . '/' }}"+ rowData.id;
      });

      $('#griddinein').on('click', 'a.gridDetail', function (e) {
        e.preventDefault();
        const rowData = grid2.row($(this).closest('tr')).data();

        window.location = "{{ url('/order/detail') . '/' }}"+ rowData.id;
      });

      $('#griddinein').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid2.row($(this).closest('tr')).data();
        const url = "{{ url('order/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Pesanan';
        const pesan = 'Apakah anda yakin ingin menghapus pesanan ini?'
        console.log(rowData, url)
        gridDeleteRow(url, title, pesan, grid);
      });

      $('#griddinein').on('click', 'a.gridVoid', function (e) {
        e.preventDefault();
        
        const rowData = grid2.row($(this).closest('tr')).data();
        const url = "{{ url('order/batal') . '/' }}" + rowData.id;
        const title = 'Batalkan Pesanan';
        const pesan = 'Alasan Pembatalan?'
        const inputan = 'ordervoidreason'
        console.log(rowData, url, inputan)
        gridDeleteInput(url, title, pesan, grid, input);
    });
  });
  </script>
@endsection
