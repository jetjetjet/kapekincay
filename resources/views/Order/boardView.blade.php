@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Pesanan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Daftar Meja</a></li>
  </ol>
@endsection

@section('content-table')
<style>
  .cards tbody tr {
    float: left;
    width: 18rem;
    margin: 0.5rem;
    border: 0.0625rem solid rgba(0, 0, 0, .125);
    border-radius: .25rem;
    box-shadow: 0.25rem 0.25rem 0.5rem rgba(0, 0, 0, 0.25);
  }

  .cards tbody td {
    display: block;
  }

  .cards thead {
    display: none;
  }
  .cards td:before {
    content: attr(data-label);
    position: relative;
    float: left;
    color: #808080;
    min-width: 4rem;
    margin-left: 0;
    margin-right: 1rem;
    text-align: left;   
  }

  tr.selected td:before {
    color: #CCC;
  }

  .table .avatar {
    width: 50px;
  }

  .cards .avatar {
    width: 150px;
    margin: 15px;
  }
</style>
  <div class="widget-content widget-content-area br-6">
  <div class="alert alert-arrow-left alert-icon-left alert-light-primary mb-4" role="alert">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
    <strong>Info!</strong> Klik icon berwana Merah untuk membuat pesanan baru dan Biru untuk melihat detail pesanan.
  </div>
    <div class="table-responsive mb-4 mt-4">
      <table id="grid" class="table table-hover cards" style="width:100%">
        <thead>
          <tr>
            <th></th>
            <th>Status</th>
            <th class="no-content"></th>
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
        ajax: {
          url: "{{ url('order/meja/lists') }}",
          dataSrc: ''
        },
        paging: false,
        ordering: false,
        dom: 
          // "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'<'float-md-right ml-2'B>f>>" +
          "<'row'<'col-sm-12'tr>>" +
          "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        buttons: {
          buttons: [{ 
            text: "Tambah Baru",
            className: 'btn',
            action: function ( e, dt, node, config ) {
              
            }
          }]
        },
        'select': 'single',
        columns: [
          { 
            'orderable': false,
            'data': null,
            'className': 'text-center',
            'render': function(data, type, full, meta){
              data = "<h4><b> Meja No." + data.boardnumber+ "</b> - Lantai "+data.boardfloor + "</h4>";
              return data;
            }
          },
          { 
            data: null,
            searchText: false,
            'render': function(data, type, full, meta){
              if(!data.boardstatus){
                return "<b>Terisi</b>";
              }
              return "<b>Kosong</b>";
            }
          },{
            'orderable': false,
            'data': null,
            'className': 'text-center',
            'render': function(data, type, full, meta){
              if(!data.boardstatus){
                let url = "{{url('/order/detail')}}"+"/"+ data.orderid;
                return '<a href="' + url + '"><span class="badge badge-primary">' +  data.orderinvoice + '</span></a>';
              }

              let url = "{{url('/order')}}" + "?idMeja=" +data.boardid+ "&mejaTeks=Meja No." + data.boardnumber+ " - Lantai "+data.boardfloor;
              return '<a href="' + url + '"><span class="badge badge-danger">Pesanan Baru</span></a>';
            }
          }
        ],
        'drawCallback': function (settings) {
          var api = this.api();
          var $table = $(api.table().node());
          
          if ($table.hasClass('cards')) {

            // Create an array of labels containing all table headers
            var labels = [];
            $('thead th', $table).each(function () {
                labels.push($(this).text());
            });

            // Add data-label attribute to each cell
            $('tbody tr', $table).each(function () {
                $(this).find('td').each(function (column) {
                  $(this).attr('data-label', labels[column]);
                });
            });

            var max = 0;
            $('tbody tr', $table).each(function () {
                max = Math.max($(this).height(), max);
            }).height(max);

          } else {
            // Remove data-label attribute from each cell
            $('tbody td', $table).each(function () {
                $(this).removeAttr('data-label');
            });

            $('tbody tr', $table).each(function () {
                $(this).height('auto');
            });
          }
        }
      });
    });
  </script>
@endsection
