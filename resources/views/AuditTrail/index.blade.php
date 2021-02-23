@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Log</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Log</a></li>
  </ol>
@endsection

@section('content-table')
  <div class="widget-content widget-content-area br-6">
    <div class="table-responsive mb-4 mt-4">
      <table id="grid" class="table table-hover" style="width:100%">
        <thead>
          <tr>
            <th>Username</th>
            <th width="20%">Url</th>
            <th>Aksi</th>
            <th>Status</th>
            <th width="25%">Log</th>
            <th width="15%">Tgl</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('js-table')
  <script>
    $(document).ready(function (){
      let grid = $('#grid').DataTable({
          ajax: "{{ url('log/grid') }}",
          processing: true,
          serverSide: true,
          paging: true,
          ordering: true,
          order: [[ 5, 'desc' ]],
          pageLength: 10,
          dom: 
            '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > >' +
            '<"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
          buttons: {
            buttons: []
          },
          oLanguage: {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Halaman _PAGE_ dari _PAGES_",
            "sSearch": '<i data-feather="search"></i>',
            "sSearchPlaceholder": "Cari...",
            "sLengthMenu": "Hasil :  _MENU_",
          },
          columns: [
            { 
              data: 'username',
              name: 'u.username'
            }, { 
              data: 'path',
              name: 'path'
            }, { 
              data: 'action',
              name: 'action'
            },{
              data: null,
              name: 'status',
              render: function(data, type, full, meta){
                let badges = data.status == 'error'
                  ? '<span class="badge badge-danger">Error</span>'
                  : '<span class="badge badge-info">Sukses</span>';
                  
                return badges;
              }
            }, { 
              data: 'messages',
              name: 'messages'
            }, { 
              data: 'createdat',
              name: 'createdat'
            }
          ]
        })
    });
  </script>
@endsection
