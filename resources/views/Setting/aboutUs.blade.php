@extends('Layout.layout-table')

@section('breadcumb')
  <div class="title">
    <h3>Tentang Kami</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Aplikasi</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Tentang Kami</a></li>
  </ol>
@endsection

@section('content-table')


<div id="privacyWrapper" class="">
                <div class="privacy-container">
                    <div class="privacyContent">

                        <div class="d-flex justify-content-between privacy-head">
                            <div class="privacyHeader">
                                <h1>Tentang Kami</h1>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam, quibusdam perferendis. Sapiente mollitia quas totam nobis vero excepturi similique ullam ipsa. Dolorem similique corporis impedit hic asperiores corrupti debitis consectetur!</p>
                            </div>
                        </div>

                        <div class="privacy-content-container">

                            <section>
                                <h5>Please read our policy carefully</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                                quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
                            </section>

                            <h5 class="policy-info-ques">What personal data we collect and why we collect it</h5>

                            <section>
                                
                                <h5>Media</h5>

                                <p>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>
                            </section>

                            <section>

                                <h5> Cookies </h5>

                                <p> If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>

                                <p> If you have an account and you log in to this site, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>

                                <p> When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select “Remember Me”, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p>

                                <p> If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p>

                            </section>
                                
                            <section>
                                <h5> Embedded content from other websites.</h5>

                                <p> Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>

                                <p> These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>
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
