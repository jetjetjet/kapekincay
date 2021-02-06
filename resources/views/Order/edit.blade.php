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
          <div class="widget-content widget-content-area">
              <div class="orderCust" style="padding-bottom:5px">
                <table>
                  <tr>
                    <th colspan="2">Your order</th>
                  </tr>
                  <tr>
                    <td style="width:80%">
                      <a type="button" title="Ubah Nama Pelanggan" class="btnTransparent" id="custname">Nama Pelanggan</a>
                    </td>
                    <td id="lblCustName">
                      Simba
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a type="button" title="Ubah Jenis Pesanan" class="btnTransparent" id="orderType">Jenis Pesanan</a>
                    </td>
                    <td id="lblTypeOrder">
1
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <a type="button" title="Ubah Nomor Meja" class="btnTransparent" id="tableNo">Nomor Meja</a>
                    </td>
                    <td id="lblTableNo">
                    2
                    </td>
                  </tr>
                </table>
              </div>
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
              <a href="" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              <a type="button" id="prosesOrder" class="btn btn-primary mt-2">Proses</a>
            </div>
            <div class="float-right">
              <button type="button" id="addToTableMenu" class="btn btn-sm btn-success d-none add-row" >
                <span class="fa fa-plus fa-fw"></span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="orderModal" tabindex="-1" data-keyboard="false" data-backdrop="static" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered"  role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Konfirmasi Pesanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row" id="mBodyOrder">
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="popSubmit" style="min-width: 75px;" class="btn btn-info btn-sm font-bold modal-add-row">Tambah</button>
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
              <tr>
                <td class="text-left">Jumlah</td>
                <td class="text-primary" >
                  <input type="text" id="menuPopupQty" name="menuPopupQty" class="menuPopupQty text-right"/>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="popSubmit" style="min-width: 75px;" class="btn btn-info btn-sm font-bold modal-add-row">Tambah</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="custModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalCustTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="cust-modal-body">
        <div class="form-row cust-body">
          
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="popSubmit" style="min-width: 75px;" class="btn btn-info btn-sm font-bold modal-add-row">Tambah</button>
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

    $('#custname').on('click', function(){
      alert(1)
    })

    $('#prosesOrder').on('click', function(){
      let orderModal = cloneModal($('#orderModal'));
      $('#uiTableInstance').remove();

      orderModal.on('show.bs.modal', function (){
        var copyTable = $targetContainer.clone();
        copyTable.attr('id', 'uiTableInstance');
        console.log(copyTable);
        orderModal.find('#mBodyOrder').html(copyTable)
          // Draws text.
          // $modal.find('.modal-title').html('Tambah');
          // $modal.find('#menuPopupText').html(paramBody['text']);
          orderModal.modal({
              backdrop: 'static',
              keyboard: true
            });

          // $('.modal-add-row')
          // .click(function (){
          //   if (actFn){
          //     actFn();
          //   }
          //   $modal.modal('hide');
          // });
      }).modal('show');
    })

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
        rowId = $("#addToTableMenu").attr('data-pId'),
        qty = $('#uiModalInstance').find('#menuPopupQty').val();

      $row.find('[id^=dtl][id$="[menuText]"]').html(rowMenuText);
      $row.find('[id^=dtl][id$="[menuPrice]"]').html(rowMenuPrice);
      $row.find('[name^=dtl][name$="[id]"]').val(rowId);
      $row.find('[name^=dtl][name$="[qty]"]').val(qty);
    })
    .on('row-removing', function (e, $row){
      
    });
    
  }
</script>
@endsection