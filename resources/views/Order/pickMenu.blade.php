@extends('Layout.layout-form')

@section('breadcumb')
<style>
.overlay { 
  background: rgba(77, 77, 77, .9);
  color: #393839;
  opacity: 1;
}
</style>
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
                <a class="nav-link active" id="justify-right-pills-home-tab" data-toggle="pill" href="#justify-right-pills-home" role="tab" aria-controls="justify-right-pills-home" aria-selected="true">Makanan</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="justify-right-pills-profile-tab" data-toggle="pill" href="#justify-right-pills-profile" role="tab" aria-controls="justify-right-pills-profile" aria-selected="false">Minuman</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="justify-right-pills-profile-paket" data-toggle="pill" href="#justify-right-pills-paket" role="tab" aria-controls="justify-right-pills-profile" aria-selected="false">Paket</a>
              </li>
            </ul>
            <div class="tab-content" id="justify-right-pills-tabContent">
              <div class="tab-pane fade show active" id="justify-right-pills-home" role="tabpanel" aria-labelledby="justify-right-pills-home-tab">
                <section class="row">
                @foreach($menu['Makanan'] as $mkn)
                  <div>
                    <a class="{{$mkn['menuavaible'] != true ? 'card' : 'menuCard'}}" data-id="{{$mkn['id']}}" data-menutext="{{$mkn['menuname']}}" data-price="{{$mkn['menuprice']}}">
                      <div class="category-tile">
                        <img width="120" height="120" src="{{ url('/').$mkn->menuimg}}" alt="Lunch">
                        <span>{{$mkn['menuname']}} {{$mkn['menuavaible'] != true ? " - Stok Kosong" : ""}}</span>
                      </div>
                    </a>
                  </div>
                @endforeach
                </section>
              </div>
              <div class="tab-pane fade" id="justify-right-pills-profile" role="tabpanel" aria-labelledby="justify-right-pills-profile-tab">
                <section class="row">
                  @foreach($menu['Minuman'] as $mkn)
                    <div>
                      <a class="{{$mkn['menuavaible'] != true ? 'card' : 'menuCard'}}" data-id="{{$mkn['id']}}" data-menutext="{{$mkn['menuname']}}" data-price="{{$mkn['menuprice']}}">
                        <div class="category-tile">
                          <img width="120" height="120" src="{{ url('/').$mkn->menuimg}}" alt="Lunch">
                          <span>{{$mkn['menuname']}} {{$mkn['menuavaible'] != true ? " - Stok Kosong" : ""}}</span>
                        </div>
                      </a>
                    </div>
                  @endforeach
                </section>
              </div>
              <div class="tab-pane fade" id="justify-right-pills-paket" role="tabpanel" aria-labelledby="justify-right-pills-paket">
              <section class="row">
                @foreach($menu['Paket'] as $mkn)
                  <div>
                    <a class="{{$mkn['menuavaible'] != true ? 'card' : 'menuCard'}}" data-id="{{$mkn['id']}}" data-menutext="{{$mkn['menuname']}}" data-price="{{$mkn['menuprice']}}">
                      <div class="category-tile">
                        <img width="120" height="120" src="{{ url('/').$mkn->menuimg}}" alt="Lunch">
                        <span>{{$mkn['menuname']}} {{$mkn['menuavaible'] != true ? " - Stok Kosong" : ""}}</span>
                      </div>
                    </a>
                  </div>
                @endforeach
                </section>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-sm-12">
          <div class="widget-content widget-content-area">
            <form id="orderMenuForm" method="post" novalidate action="{{url('/order/save')}}">
              <div class="orderCust" style="padding-bottom:5px">
                <table>
                  <tr>
                    <th colspan="2"><u>Pesanan {{ $data->orderinvoice }}</u></th>
                  </tr>
                  <tr>
                    <th style="width: 40%"> Nama Pelanggan </th>
                    <td id="lblCustName">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                      <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
                      <input type="hidden" name="ordercustname" value="{{ old('ordercustname', $data->ordercustname) }}" required>
                      <p id="custname">{{ old('ordercustname', $data->ordercustname) }}</p>
                    </td>
                  </tr>
                  <tr>
                    <th> Jenis Pesanan </th>
                    <td id="lblTypeOrder">
                      <input type="hidden" name="ordertype" value="{{ old('ordertype', $data->ordertype) }}" required>
                      <p id="jnsPesanan">{{ old('ordertype', $data->ordertype) }}</p>
                    </td>
                  </tr>
                  <tr>
                    <th>Nomor Meja</th>
                    <td id="lblTableNo">
                      <input type="hidden" name="orderboardid" value="{{ old('orderboardid', $data->orderboardid) }}" required>
                      <p id="noMeja">{{ old('orderboardtext', $data->orderboardtext) }}</p>
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
                      <th>Total</th>
                      <th></th>
                      <th>sam</th>
                    </tr>
                  </thead>
                  <tbody>
                   @foreach ($data->subOrder as $sub)
                    @include('Order.subOrder', Array('rowIndex' => $loop->index))
                   @endforeach
                  </tbody>
                </table>
              </div>
              <div class="float-left">
                <h2>Total =</h2>
                </div>
              <div class="float-right">
                <h2 id="idTotal">0</h2>
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
      <div class="row fixed-bottom">
        <div class="col-sm-12 ">
          <div class="widget-content widget-content-area">
            <div class="float-left">
              <a href="" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
            </div>
            <div class="float-right">
              <a href="" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah {{isset($data->id) ? 'Meja' : 'Pelanggan'}}</a>
              <a type="button" id="prosesOrder" class="btn btn-primary mt-2">Simpan</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="custModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Ubah Detail Pesanan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <table class="table mb-4">
            <tbody>
              <div class="widget-content widget-content-area">
                <form id="custForm">
                  <div class="form-row">
                    <div class="col-md-12 mb-2">
                      <label for="ordercustname">Nama Pelanggan</label>
                      <input type="text" class="form-control" name="ordercustname" value="{{ old('ordercustname', $data->ordercustname) }}" placeholder="Nama Pelanggan" {{ !empty($data->id) ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-12 mb-2">
                      <label for="ordertype">Tipe Pesanan</label>
                      @if(!isset($data->id))
                        <select id="orderType" class="form-control orderType" name="ordertype">
                          <option value="DINEIN">Makan Ditempat</option>
                          <option value="TAKEAWAY">Bungkus</option>
                        </select>
                      @else
                        <input type="hidden" id="orderType" name="ordertype" class="form-control" value="{{ old('ordertype', $data->ordertype) }}" readonly>
                        <input type="text" class="form-control" value="{{ $data->ordertype == 'DINEIN' ? 'Makan Ditempat' : 'Bungkus' }}" readonly>
                      @endif
                    </div>
                    <div class="col-md-12 mb-4 divMeja">
                      <label for="orderboardid">Nomor Meja</label>
                      <select class="form-control form-control-sm cariMeja" id="cariMeja" name="orderboardid">
                      </select>
                    </div>
                  </div>
                </form>
              </div>
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="custButton" style="min-width: 75px;" class="btn btn-success btn-sm font-bold modal-add-row">Tambah</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Menu</h5>
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
              <tr>
                <td class="text-left">Catatan</td>
                <td class="text-primary" >
                  <input type="text" id="menuRemark" name="menuRemark" class="form-control"/>
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

<table id="tabel" class="row-template d-none">
    @include('Order.subOrder')
</table>
@endsection

@section('js-form')
<script>
  $(document).ready(function (){
    let $targetContainer = $('#detailOrder');
    setupTableGrid($targetContainer);

    $('#prosesOrder').on('click', function(){
      $('#prosesOrder').attr('dissabled');
      $('#orderMenuForm').submit();
    })

    $('#headerOrder').on('click', function(){
      let idMeja, textMeja;
      $(this).attr('data-toggle', 'modal');
      $(this).attr('data-target', '#uiModalInstance');

      $.fn.modal.Constructor.prototype._enforceFocus = function() {};
      let $modal = cloneModal($('#custModal'));

      

      $modal.on('show.bs.modal', function (){
        let oType = $('[name="ordertype"]').val(),
          idBoard = $('[name="orderboardid"]').val();

        $modal.find('[name="ordertype"]').val(oType);
        showTypeO($modal, oType);
        if(idBoard){
          changeOptSelect2($('.cariMeja'), "{{ Url('/meja/cariTersedia') }}" + "/" + idBoard)
        }
        
      }).modal('show');

      $modal.find('#custButton').on('click',function(){
        let custNameP = $modal.find('[name="ordercustname"]').val(),
          orderTypeP = $modal.find('[name="ordertype"]').val();

        $('[name="ordercustname"]').val(custNameP);
        $('#custname').html(custNameP);

        $('#jnsPesanan').html(lblOrderType(orderTypeP));
        $('[name="ordertype"]').val(orderTypeP);

        if (idMeja != null){
          $('[name="orderboardid"]').val(idMeja);
          $('#noMeja').html(textMeja);
        }
        
        
        $modal.modal('hide');
      })

      $('.cariMeja').on('select2:select', function (e) {
        textMeja = e.params.data.text;
        idMeja = e.params.data.id;
      });

      $modal.find('.orderType').on('change',function(){
        let val = $(this).val();
        showTypeO($modal, val);
      });
    })

    $('.menuCard').on('click', function(){
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

    caclculatedOrder()
  });

  function setupTableGrid($targetContainer)
  {
    // Setups add grid. 
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
    let rowMenuText = $("#addToTableMenu").attr('data-pMenuText'),
        rowMenuPrice = $("#addToTableMenu").attr('data-pMenuPrice'),
        rowId = $("#addToTableMenu").attr('data-pId'),
        qty = $('#uiModalInstance').find('#menuPopupQty').val(),
        remark = $('#uiModalInstance').find('#menuRemark').val(),
        tprice = qty*rowMenuPrice;
      $row.find('[id^=dtl][id$="[odmenutext]"]').html(rowMenuText);
      $row.find('[id^=dtl][id$="[odprice]"]').html(rowMenuPrice);
      $row.find('[id^=dtl][id$="[odtotalprice]"]').html(tprice);
      $row.find('[id^=dtl][id$="[odremark]"]').html(remark);
      $row.find('[name^=dtl][name$="[odmenuid]"]').val(rowId);
      $row.find('[name^=dtl][name$="[odmenutext]"]').val(rowMenuText);
      $row.find('[name^=dtl][name$="[odqty]"]').val(qty);
      $row.find('[name^=dtl][name$="[odprice]"]').val(rowMenuPrice);
      $row.find('[name^=dtl][name$="[odremark]"]').val(remark);
      caclculatedOrder()
    })
    .on('row-removing', function (e, $row){
      caclculatedOrder()
    });
  }

  function lblOrderType(val){
    let lbl = "";
    if(val == 'DINEIN'){
      lbl = 'Makan Di Tempat';
    } else {
      lbl = 'Bungkus';
        $('[name="orderboardid"]').val("");
        $('#noMeja').html("");
    }
    return lbl;
  }

  function showTypeO($modal, val){
    if(val == "TAKEAWAY"){
      $('.divMeja').addClass('d-none');
      $modal.find('.cariMeja').select2().val(null).trigger('change');
    } else {
      $('.divMeja').removeClass('d-none')
    }
    inputSearch('.cariMeja', "{{ Url('/meja/cariTersedia') }}", 'resolve', function(item) {
      return {
        text: item.text,
        id: item.id
      }
    });
  }

  function caclculatedOrder(){
    let gridRow = $('#detailOrder').find('[id^=dtl][id$="[odmenutext]"]').closest('tr');
    
    let totalPrice = 0;

    gridRow.each(function(){
      let price = $(this).find('[id^=dtl][id$="[odtotalprice]"]').html();
      totalPrice += Number(price);
    });
    $('#idTotal').html(totalPrice);
    console.log(totalPrice);
  }
</script>
@endsection