@extends('Layout.layout-form')

@section('breadcumb')
  <link rel="stylesheet" href="{{ url('/') }}/plugins/font-icons/fontawesome/css/regular.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/font-icons/fontawesome/css/fontawesome.css">
  <style>
    .overlay { 
      background: rgba(77, 77, 77, .9);
      color: #393839;
      opacity: 1;
    }
    .dtl-order td, .dtl-order th {
        padding: 0;
    }
  </style>
  <div class="title">
    <h3>Pesanan</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Pesanan</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Proses Pesanan</a></li>
  </ol>
@endsection

@section('content-form')
<div class="widget-content widget-content-area br-4">
  <div class="col-xl-12 col-lg-12 col-md-12">
    <div class="statbox box box-shadow">
      @if(isset($data->ordervoidedat))
        <div class="alert alert-warning" role="alert">
          <strong>Pesanan Dibatalkan!</strong>
            <ul>
              <li>Dibatalkan Oleh: </li>
              <li>Dibatalkan Pada: {{$data->ordervoidedat}}</li>
              <li>Alasan: {{$data->ordervoidedreason}}</li>
            </ul>
        </div>
      @endif
      <div class="row">
        <div class="col-md-7 col-sm-12">
          <div class="widget-content pill-justify-right">
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
        <div class="col-md-5 col-sm-12">
          <div class="widget-content widget-content-area" style="margin-bottom:25px">
            <form id="orderMenuForm" method="post" novalidate action="{{url('/order/save')}}">
              <div class="orderCust" style="padding-bottom:5px">
              <!-- No. Pesanan {{ isset($data->orderinvoice) ? $data->orderinvoice : "" }} -->
                @if(isset($data->id))
                <div class="form-group row divMeja">
                  <div class="col-sm-12">
                    <p><h4>Nomor Pesanan: <b>{{ old('orderinvoice', $data->orderinvoice) }}</b></h4></p>
                  </div>
                </div>
                @endif
                <div class="form-group row">
                  <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Jenis Pesanan</label>
                  <div class="col-sm-8">
                    <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                    @if(!$data->id)
                      <select id="orderType" class="custom-select custom-select-sm" name="ordertype">
                        <option value="DINEIN" >Makan Ditempat</option>
                        <option value="TAKEAWAY" "{{ $data->ordertype == 'TAKEAWAY' ?'selected':'' }}">Bungkus</option>
                      </select>
                    @else
                      <input type="text" id="boardText" value="{{ $data->ordertypetext }}" name="ordertype" class="form-control form-control-sm" readonly>
                    @endif
                  </div>
                </div>
                <div class="form-group row divMeja">
                  <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">No. Meja</label>
                  <div class="col-sm-8">
                    <input type="hidden" id="boardId" value="{{ old('orderboardid', $data->orderboardid) }}" name="orderboardid" id="colFormLabelSm">
                    <input type="text" id="boardText" value="{{ old('orderboardtext', $data->orderboardtext) }}" name="orderboardtext" class="form-control form-control-sm" readonly>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <table id="detailOrder" class="table table-hover dtl-order">
                  <thead>
                    <tr>
                      <th width="40%">Menu</th>
                      <th>Harga</th>
                      <th style="width:50px">qty</th>
                      <th>Total</th>
                      <th>Cttn</th>
                      <th></th>
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
                <input type="hidden" name="orderprice" value="{{ old('orderprice', $data->orderprice) }}" required>
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
      @if(!isset($data->ordervoidedat))
      <div class="row fixed-bottom">
        <div class="col-sm-12 ">
          <div class="widget-content widget-content-area" style="padding:10px">
            <div class="float-left">
              @if(Perm::can(['order_hapus']) && ($data->orderstatus == 'PROCEED' || $data->orderstatus == 'ADDITIONAL'))
                <a href="" id="deleteOrder" type="button" class="btn btn-danger mt-2">Hapus</a>
              @endif
              @if(Perm::can(['order_void']) && ($data->orderstatus == 'ADDITIONAL' || $data->orderstatus == 'COMPLETED' || $data->orderstatus == 'PAID'))
                <a href="" id="void" type="button" class="btn btn-danger mt-2">Batalkan Pesanan</a>
              @endif
            </div>
            <div class="float-right">
              <a href="{{url('/order/meja/view')}}" type="button" class="btn btn-warning mt-2">Kembali</a>
              @if(Perm::can(['order_simpan']))
                <a href="" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Meja</a>
                <a type="button" id="prosesOrder" class="btn btn-primary mt-2">Simpan</a>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>

<div class="modal fade" id="mejaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Ubah Meja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-row">
          <div class="col-md-12">
            <label for="orderboardid">Nomor Meja</label>
            <select class="form-control form-control-sm cariMeja" id="cariMeja" name="orderboardid">
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Batal</button>
        <button type="button" id="custButton" style="min-width: 75px;" class="btn btn-success btn-sm font-bold modal-add-row">Ubah</button>
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
                  <input type="number" class="form-control form-control-sm text-right" id="menuPopupQty" name="menuPopupQty" class="menuPopupQty text-right"/>
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
    const query = window.location.search.substring(1);
    const urlParams = new URLSearchParams(query);
    const urlMeja = urlParams.get('idMeja');
    const urlMejaTeks = urlParams.get('mejaTeks');

    if(urlMeja && urlMejaTeks)
    {
      $('[name="orderboardid"]').val(urlMeja);
      $('[name="orderboardtext"]').val(urlMejaTeks);
    }

    //VOID
    $('#void').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('order/batal') . '/' }}" + '{{$data->id}}';
      const title = 'Batalkan Pesanan';
      const pesan = 'Alasan batal?'
      gridDeleteInput2(url, title, pesan, null);
    });

    //Delete deleteOrder
    $('#deleteOrder').on('click', function (e) {
      e.preventDefault();
      
      const url = "{{ url('order/hapus') . '/' }}" + '{{$data->id}}';
      const title = 'Hapus Pesanan';
      gridDeleteInput3(url, title, null, function(callb){
        setTimeout(() => {
          window.location = "{{ url('/order/meja/view') }}";
        }, 2000);
      });
    });

    let $targetContainer = $('#detailOrder');
    setupTableGrid($targetContainer);

    $('#prosesOrder').on('click', function(){
      $('#prosesOrder').attr('dissabled');
      $('#orderMenuForm').submit();
    })
    
    $('#orderType').on('change',function(){
      let val = $(this).val();
      if(val == "TAKEAWAY"){
        $('.divMeja').addClass('d-none');
        $('#headerOrder').addClass('d-none');
        $modal.find('.cariMeja').select2().val(null).trigger('change');
      } else {
        $('.divMeja').removeClass('d-none')
        $('#headerOrder').removeClass('d-none');
      }
    });

    $('#headerOrder').on('click', function(){
      let idMeja, textMeja;
      $(this).attr('data-toggle', 'modal');
      $(this).attr('data-target', '#uiModalInstance');

      $.fn.modal.Constructor.prototype._enforceFocus = function() {};
      let $modal = cloneModal($('#mejaModal'));

      $modal.on('show.bs.modal', function (){
        let idBoard = $('[name="orderboardid"]').val();

        if(idBoard){
          changeOptSelect2($('.cariMeja'), "{{ Url('/meja/cariTersedia') }}" + "/" + idBoard)
        }

        inputSearch('.cariMeja', "{{ Url('/meja/cariTersedia') }}", 'resolve', function(item) {
          return {
            text: item.text,
            id: item.id
          }
        });
        
      }).modal('show');

      $modal.find('#custButton').on('click',function(){

        if (idMeja != null){
          $('[name="orderboardid"]').val(idMeja);
          $('[name="orderboardtext"]').val(textMeja);
        }
        
        $modal.modal('hide');
      })

      $('.cariMeja').on('select2:select', function (e) {
        textMeja = e.params.data.text;
        idMeja = e.params.data.id;
      });
    })

    
    @if(!isset($data->ordervoidedat) && empty($data->orderpaid))
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
    @endif

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
      $row.find('[id^=dtl][id$="[odprice]"]').html(formatter.format(rowMenuPrice));
      $row.find('[id^=dtl][id$="[odtotalprice]"]').html(formatter.format(tprice));
      $row.find('[name^=dtl][name$="[odtotalprice]"]').val(tprice);
      $row.find('[id^=dtl][id$="[odremark]"]').html(remark);
      $row.find('[name^=dtl][name$="[odmenuid]"]').val(rowId);
      $row.find('[name^=dtl][name$="[odmenutext]"]').val(rowMenuText);
      $row.find('[name^=dtl][name$="[odqty]"]').val(qty);
      $row.find('[name^=dtl][name$="[odprice]"]').val(rowMenuPrice);
      $row.find('[name^=dtl][name$="[odremark]"]').val(remark);
      window.setTimeout(() => {
        caclculatedOrder()        
      }, 0);
    })
    .on('row-removing', function (e, $row){
      window.setTimeout(() => {
        caclculatedOrder()        
      }, 0);
    })
    .on('row-delivering', function (e, $row){
      let idSub = $row.find('[name^=dtl][name$="[id]"]').val(),
        url = "{{ url('order/delivered') . '/'}}" + '{{$data -> id}}' + "/" + idSub;
      $.post(url, function (data){
        var msg = data.messages[0]
          toast({
            type: data.status,
            title: msg,
            padding: '2em',
          });
          // location.reload();
          if(data.status == 'success'){
            location.reload();
            // $row.find('[id^=dtl][id$="[delivRow]"]').addClass('d-none');
            // $row.find('[id^=dtl][id$="[deleteRow]"]').addClass('d-none');
          }
      });
    })
    .on('row-updating', function (e, $row){
      let newQty = $row.find('[name^=dtl][name$="[odqty]"]').val(),
        price = $row.find('[name^=dtl][name$="[odprice]"]').val();
      const newTotalPrice = Number(newQty) * Number(price);
      $row.find('[id^=dtl][id$="[odtotalprice]"]').html(formatter.format(newTotalPrice));
      $row.find('[name^=dtl][name$="[odtotalprice]"]').val(newTotalPrice);

      caclculatedOrder()    
    });
  }

  function caclculatedOrder(){
    let gridRow = $('#detailOrder').find('[id^=dtl][id$="[odmenutext]"]').closest('tr');
    
    let totalPrice = 0;

    gridRow.each(function(){
      let price = $(this).find('[name^=dtl][name$="[odtotalprice]"]').val();
      totalPrice += Number(price);
    });
    $('#idTotal').html(formatter.format(totalPrice));
    $('[name="orderprice"]').val(totalPrice);
  }

  const toast = swal.mixin({
    toast: true,
    position: 'center',
    showConfirmButton: false,
    timer: 3000,
    padding: '2em'
  });
</script>
@endsection