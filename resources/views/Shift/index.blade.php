@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Shift</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Shift</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>Shift</th>
            <th>Karyawan</th>
            <th>Tanggal Buka</th>
            <th>Tanggal Tutup</th>
            <th>Catatan</th>
            <th class="no-content" style="width:100px"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <th>Shift</th>
            <th>Karyawan</th>
            <th>Tanggal Buka</th>
            <th>Tanggal Tutup</th>
            <th>Catatan</th>
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

      let grid = $('#grid').DataTable({
        ajax: {
          url: "{{ url('shift/grid') }}",
          dataSrc: ''
      },
        dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "Tambah Shift",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/shift/detail') }}";
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
        "pageLength": 15,
        columns: [
          { 
            data: 'shiftindex',
            searchText: true
          },
          { 
            data: 'username',
            searchText: true
          },
          { 
            data: 'shiftstart',
            searchText: true
          },
          { 
              data: 'shiftclose',
              searchText: true
          },
          { 
              data: 'shiftenddetail',
              searchText: true
          },
         {
            data:null,
            render: function(data, type, full, meta){
              if(data.shiftclose != null){
              return '<a href="#" title="View" class="gridView"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye p-1 br-6 mb-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
              
              }else{
                return '<a href="#" title="View" class="gridView"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye p-1 br-6 mb-1"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>'+
              '<a href="#" title="Delete" class="gridDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>'+
              '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>'+
              '<a href="#" title="Close" class="gridClose"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle p-1 br-6 mb-1"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>';
              
              }
            }
              
              
           
          }
        ]
    });
      $('#grid').on('click', 'a.gridEdit', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/shift/edit') . '/' }}" + rowData.id;
    });

      $('#grid').on('click', 'a.gridClose', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/shift/close') . '/' }}" + rowData.id;
    });
    
      $('#grid').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('shift/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Shift';
        const pesan = 'Alasan hapus?'
        console.log(rowData, url)
        gridDeleteInput(url, title, pesan, grid);
      });
      $('#grid').on('click', 'a.gridnoDelete', function (e) {
        e.preventDefault();
        
        const title = 'Shift Sudah Ditutup';
        const pesan = '';
        const type = 'error';
        sweetAlert(title, pesan, type);
      });
      $('#grid').on('click', 'a.gridnoEdit', function (e) {
        e.preventDefault();
        
        const title = 'Shift Sudah Ditutup';
        const pesan = '';
        const type = 'error';
        sweetAlert(title, pesan, type);
      });
      $('#grid').on('click', 'a.gridnoClose', function (e) {
        e.preventDefault();
        
        const title = 'Shift Sudah Ditutup';
        const pesan = '';
        const status = 'error';
        sweetAlert(title, pesan, status);
      });
    });

  </script>
@endsection
