@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>User</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} User</a></li>
  </ol>
@endsection

@section('content-form')
<div class="widget-content widget-content-area br-6">
  <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="statbox widget box box-shadow">
      <div class="row">
        <div class="col-md-8 col-sm-12">
          <div class="widget-content widget-content-area pill-justify-right">
            <ul class="nav nav-pills mb-3 mt-3 justify-content-end" id="justify-right-pills-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="justify-right-pills-home-tab" data-toggle="pill" href="#justify-right-pills-home" role="tab" aria-controls="justify-right-pills-home" aria-selected="true">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="justify-right-pills-profile-tab" data-toggle="pill" href="#justify-right-pills-profile" role="tab" aria-controls="justify-right-pills-profile" aria-selected="false">Profile</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="justify-right-pills-contact-tab" data-toggle="pill" href="#justify-right-pills-contact" role="tab" aria-controls="justify-right-pills-contact" aria-selected="false">Contact</a>
              </li>
            </ul>
            <div class="tab-content" id="justify-right-pills-tabContent">
              <div class="tab-pane fade show active" id="justify-right-pills-home" role="tabpanel" aria-labelledby="justify-right-pills-home-tab">
                <section class="row">
                  <div>
                    <a id="menuCard" data-id="1" data-menutext="Nasi Goreng" data-price="15.000">
                      <div class="category-tile">
                        <img width="120" height="120" src="https://usmile581.github.io/Bistro_Restaurant/images/menu/B/B.jpg" alt="Lunch">
                        <span>Nasi Goreng</span>
                      </div>
                    </a>
                  </div>
                </section>
              </div>
              <div class="tab-pane fade" id="justify-right-pills-profile" role="tabpanel" aria-labelledby="justify-right-pills-profile-tab">
                dsa
              </div>
              <div class="tab-pane fade" id="justify-right-pills-contact" role="tabpanel" aria-labelledby="justify-right-pills-contact-tab">
                asd
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="widget-header">                                
            <div class="row">
              <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                <h4>Pesanan</h4>
              </div>                                                                        
            </div>
          </div>
          <div class="widget-content widget-content-area">
            <form class="needs-validation" method="post" novalidate action="{{ url('/meja/simpan') }}">
              <div class="form-row">
                <div class="col-12">
                  <label for="ordercustname">Nama Pelanggan</label>
                  <div class="input-group">
                    <input type="text" name="ordercustname" value="" class="form-control form-control-small" id="ordercustname" placeholder="Nama Pelanggan" required>
                  </div>
                </div> 
              </div>
              <hr />
              <div class="form-row">
                <table id="detailOrder" class="table table-hover">
                  <thead>
                    <tr>
                      <th width="40%">Menu</th>
                      <th>Harga</th>
                      <th style="width:50px">Jumlah</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
              <div class="float-right">
                <input type="number" name="total" value="" class="form-control form-control-small" id="total" placeholder="Total" readonly>
                <a href="" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
                <button class="btn btn-primary mt-2" type="submit">Simpan</button>
              </div>
              <div class="float-right">
                <button type="button" id="addToTableMenu" class="btn btn-sm btn-success d-none add-row" >
                  <span class="fa fa-plus fa-fw"></span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <table class="table mb-4">
            <tbody>
              <tr>
                <td class="text-left">Pilihan Menu</td>
                <td class="text-primary" id="menuPopupText"></td>
              </tr>
              <tr>
                <td class="text-left">Harga</td>
                <td class="text-primary" id="menuPopupPrice"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <a type="button" style="min-width: 75px;" class="btn btn-info btn-sm font-bold add-row">Tambah</a>
      </div>
    </div>
  </div>
</div>

<table class="row-template d-none">
    @include('Order.subOrder')
</table>
@endsection

@section('js-form')
<script>
  $(document).ready(function (){
    let $targetContainer = $('#detailOrder');
    setupTableGrid($targetContainer);

    $('#menuCard').on('click', function(){
      let menuPrice = $(this).attr('data-price'),
          menuText = $(this).attr('data-menutext'),
          menuId = $(this).attr('data-id');

      let bodyPopup = {
        'text' : menuText,
        'price' : menuPrice
      };
      
      showPopupOrder(bodyPopup, function(){
        $("#addToTableMenu").attr("data-pMenuText",menuText);
        $("#addToTableMenu").attr("data-pMenuPrice",menuPrice);
        $("#addToTableMenu").attr("data-pId",menuId);
        $('#addToTableMenu').trigger('click');
      });
    });
  });

  function setupTableGrid($targetContainer)
  {
    // Setups add grid.
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
      let rowMenuText = $("#addToTableMenu").attr('data-pMenuText'),
          rowMenuPrice = $("#addToTableMenu").attr('data-pMenuPrice'),
          rowId = $("#addToTableMenu").attr('data-data-pId');

      $row.find('[id^=dtl][id$="[menuText]"]').html(rowMenuText);
      $row.find('[id^=dtl][id$="[menuPrice]"]').html(rowMenuPrice);
      $row.find('[name^=dtl][name$="[id]"]').html(rowId);
    })
    .on('row-removing', function (e, $row){
      
    });
  }
</script>
@endsection