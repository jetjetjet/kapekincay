@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Meja</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Meja</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>Nomor Meja</th>
            <th>Lantai</th>
            <th>Kapasitas</th>
            <th class="no-content"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
        <tfoot>
          <tr>
            <th>Nomor Meja</th>
            <th>Lantai</th>
            <th>Kapasitas</th>
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
          url: "{{ url('meja/grid') }}",
          dataSrc: ''
      },
        dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "Tambah Baru",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/meja/detail') }}";
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
        "pageLength": 10,
        columns: [
          { 
            data: 'number',
            render: function (data, type, full, meta){
              let link =  "" + full.id ;
              return '<a href="' + link + '">' + full.number + '</a>';
            },
            searchText: true
          },
          { 
              data: 'floor',
              searchText: true
          },
          { 
              data: 'space',
              searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              return '<a href="#" title="Delete" class="gridDelete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash p-1 br-6 mb-1"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>';
            }
          }
        ]
      });
      $('#grid').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('meja/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Data Meja';
        const pesan = 'Apakah anda yakin ingin menghapus data ini?'
        console.log(rowData, url)
        gridDeleteRow(url, title, pesan, grid);
      });
    });
  </script>
@endsection
