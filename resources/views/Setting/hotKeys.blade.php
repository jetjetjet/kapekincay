@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Tombol Pintas</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Tombol Pintas</a></li>
  </ol>
@endsection

@section('content-table')


<div id="privacyWrapper" class="">
                <div class="privacy-container">
                    <div class="privacyContent">

                        <div class="d-flex justify-content-between privacy-head">
                            <div class="privacyHeader">
                                <h1>Tombol Pintas</h1>
                            </div>
                        </div>

                        <div class="privacy-content-container">

                            <section>
                                <h5>Global (Semua Halaman)</h5>
                                <p><b style="color: #007bff;">Esc</b> Atau <b style="color: #007bff;">*</b> Untuk membuka laci</p>
                                <p><b style="color: #007bff;">P</b> Untuk ping ke printer</p>
                            </section>
                            <hr>
                            <section>
                              <h5>Halaman Meja</h5>
                              <p><b style="color: #007bff;">Enter</b> Untuk membuat pesanan bungkus baru</p>
                              <p><b style="color: #007bff;">+</b> dan <b style="color: #007bff;">-</b> Untuk bergeser tabel pesanan bungkus</p>
                            </section>
                            <hr>
                            <section>
                              <h5>Halaman Pembayaran</h5>
                              <p><b style="color: #007bff;">Enter</b> Untuk melanjutkan pembayaran setelah memasukkan total pembayaran</p>
                              <p><b style="color: #007bff;">Backspace</b> Untuk kembali ke halaman meja</p>
                              <p><b style="color: #acb0c3;">--------------------------------------</b></p>
                              <p><i style="color: #acb0c3;">Saat Muncul Halaman Konfirmasi</i></p>
                              <p><b style="color: #007bff;">Enter</b> Untuk melanjutkan pembayaran di tampilan konfirmasi</p>
                              <p><b style="color: #007bff;">Backspace</b> Untuk menutup konfirmasi pembayaran</p>
                            </section>



                        </div>

                    </div>
                </div>
            </div>


            
@endsection

@section('js-table')
  <script>
    $(document).ready(function (){
      let grid = $('#grid').DataTable({
        ajax: {
          url: "{{ url('setting/grid') }}",
          dataSrc: ''
      },
        dom: '<"row"<"col-md-12"<"row"<"col-md-1"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
        buttons: {
            buttons: [{ 
              text: "Tambah Pelanggan",
              className: 'btn',
              action: function ( e, dt, node, config ) {
                window.location = "{{ url('/cust/detail') }}";
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
            data: 'settingcategory',
            searchText: true
          },
          { 
              data: 'settingkey',
              searchText: true
          },
          { 
              data: 'settingvalue',
              searchText: true
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              let icon = "";
              
              if(data.can_save)
                icon += '<a href="#" title="Edit" class="gridEdit"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit p-1 br-6 mb-1"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>';
              
              return icon;
            }
          }
        ]

      });
      $('#grid').on('click', 'a.gridEdit', function (e) {
        e.preventDefault();
        const rowData = grid.row($(this).closest('tr')).data();

        window.location = "{{ url('/setting/detail') . '/' }}" + rowData.id;
      });
      $('#grid').on('click', 'a.gridDelete', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('cust/hapus') . '/' }}" + rowData.id;
        const title = 'Hapus Data Pelanggan';
        const pesan = 'Apakah anda yakin ingin menghapus data ini?'
        console.log(rowData, url)
        gridDeleteRow(url, title, pesan, grid);
      });
    });
  </script>
@endsection
