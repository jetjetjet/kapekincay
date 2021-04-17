@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Order</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/order').'/'.$data->id }}">Order</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Pembayaran</a></li>
  </ol>
@endsection

@section('content-form')
  @if(isset($data->ordervoidedat))
    <div class="alert alert-danger" role="alert">
      <strong>Pesanan Dibatalkan!</strong>
        <ul>
          <li>Dibatalkan Oleh: <b>{{$data->ordervoidedusername}}</b></li>
          <li>Dibatalkan Pada: {{$data->ordervoidedat}}</li>
          <li>Alasan: {{$data->ordervoidreason}}</li>
        </ul>
    </div>
  @endif
  <div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox">
          <div class="widget-content">
              @if($data->ordertype == 'DINEIN')
                <div class="form-row">
                  <div class='col-12'>
                    <h3 style="color:#1b55e2"><b>{{$data->orderboardtext}}</b></h3>
                  </div>
                </div>
              @endif
              <hr class="mt-0 mb-3"/>
              <div class="form-row">
                <div class='col-md-2 col-sm-6 xs-6'>
                  <h4>Nomor Pesanan</h4>
                </div>
                <div class='col-md-10 col-sm-6 xs-6'>
                  <h4>: <b>{{$data->orderinvoice}}</b></h4>
                </div>
              </div>
              <div class="form-row">
                <div class='col-md-2 col-sm-6 xs-6'>
                  <h4>Tipe Pesanan </h4>
                </div>
                <div class='col-md-10 col-sm-6 xs-6'>
                  <h4>: <b> {{$data->ordertype == 'DINEIN' ? 'Makan Ditempat' : 'Bungkus' }} </b></h4>
                </div>
              </div>
              <div class="form-row">
                <div class='col-md-2 col-sm-6 xs-6'>
                  <h4>Tgl. Pesanan</h4>
                </div>
                <div class='col-md-10 col-sm-6 xs-6'>
                  <h4>: <b> {{$data->orderdate }} </b></h4>
                </div>
              </div>
              @if(isset($data->orderpaiddate))
                <div class="form-row">
                  <div class='col-md-2 col-sm-6 xs-6'>
                    <h4>Tgl. Pembayaran</h4>
                  </div>
                  <div class='col-md-10 col-sm-6 xs-6'>
                    <h4>: <b> {{$data->orderpaiddate }} </b></h4>
                  </div>
                </div>
              @endif
              @if(isset($data->orderpaymentmethod))
                <div class="form-row">
                  <div class='col-md-2 col-sm-6 xs-6'>
                    <h4>Tipe Pembayaran</h4>
                  </div>
                  <div class='col-md-10 col-sm-6 xs-6'>
                    <h4>: <b> {{$data->orderpaymentmethod }} </b></h4>
                  </div>
                </div>
              @endif
              <hr class="mt-0 mb-0"/>
              <section>
                <div class="form-row">
                  <div class="table-responsive">
                    <h3 class="mt-2 mb-10" style="font-weight: 300; text-align: left; padding-left: 1.8rem;">Detail Pesanan</h3>
                    <table id=grid class="table table-bordered mb-20">
                        <thead>
                          <th>Menu</th>
                          <th>Qty</th>
                          <th>Harga</th>
                          <th>Promo</th>
                          <th>Total</th>
                          <th>Catatan</th>
                          @if(!$data->orderpaid)
                            <th>Status Pesanan</th>
                          @endif
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <form id="orderMenuForm" method="post" novalidate action="{{url('/order/bayar')}}/{{$data->id}}">
                      <div class="col-md-12">
                        <div class="form-group col-md-3 float-left">
                          @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')                                           
                            <h4>Status Pesanan : <b>{{$data->orderstatuscase}}</b></h4>                     
                          @elseif($data->orderstatus == 'COMPLETED'|| $data->ordertype == 'TAKEAWAY')
                            <h4>Jenis Pembayaran</h4>
                            <select class="form-control mousetrap" id="type" name="orderpaymentmethod">
                              <option value="Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Tunai' ? ' selected' : '' }}> Tunai</option>
                              <option value="Non-Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Non-Tunai' ? ' selected' : '' }}> Non-Tunai</option>
                            </select>                     
                          @endif
                        </div>
                        <div class="float-right col-md-3">
                          <input type="hidden" id="afterPrice" value="{{$data->orderprice}}">
                          <input type="hidden" id="startPrice" value="{{$data->orderprice}}">
                          <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                          <input type="hidden" name="username" id="name" value="{{ session('username') }}" />
                            @if(isset($data->orderdiscountprice))
                            <h3 id="price">Total : <b class='float-right'>{{ number_format($data->orderprice - $data->orderdiscountprice,0)}}</b><h6><i class='float-right' style='color:#acb0c3'><s>{{ number_format($data->orderprice,0) }}</s></i></h6> </b></h3>
                            @else
                            <h3 id="price">Total :<b class="float-right"> {{ number_format($data->orderprice,0) }}</b></h3>
                            @endif
                            @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')

                            @elseif($data->orderstatus == 'COMPLETED' || $data->ordertype == 'TAKEAWAY')
                              <label class="new-control new-checkbox checkbox-primary">
                                  <input id="cekDisc" type="checkbox" class="new-control-input">
                                  <span class="new-control-indicator"></span>Diskon
                              </label>                                  
                              <input type="number" class="form-control text-right mousetrap d-none" required name="orderdiscountprice" id="diskon" placeholder="Diskon">  
                              <input autofocus type="number" class="form-control text-right mousetrap mb-2" required name="orderpaidprice" id="bayar" placeholder="Jumlah Uang">                                              
                              <h3 id="kembalian">Kembalian : <b class="float-right">0</b></h3>                       
                            @else      
                            <input type="hidden" id="bayar" value="-1" />
                            @endif          
                        </div>
                      </div>
                    </form>
                    <form id="miniform" method="post" novalidate action="{{url('/order/bayar/cetak')}}/{{$data->id}}">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                      <input type="hidden" name="username" id="name" value="{{ session('username') }}" />
                    </form>
                  </div>
                </div>
              </section>
              
          </div>
          <div id="fixbot" class="row fixed-bottom">
            <div class="col-sm-12 ">
              <div class="widget-content widget-content-area" style="padding:10px">
                <div class="float-right">
                  <a href="{{url('/order/meja/view')}}" id="back" type="button" class="btn btn-warning mt-2">Kembali</a>
                  @if($data->orderstatus == 'PAID')
                    <button id="print" class="btn btn-success mt-2">Cetak</button>
                  @endif
                  @if(!($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID'))
                    @if($data->ordertype == 'TAKEAWAY' || Perm::can(['order_pelayan']))
                    <a href="{{ url('/order').'/'.$data->id }}" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Pesanan</a>
                    @endif
                    <!-- <a href="" type="button" id="drawer" class="btn btn-success mt-2">Buka Laci</a> -->
                    @if(Perm::can(['order_pembayaran']))
                      <button disabled id="drawer" class="btn btn-primary mt-2">&nbsp;&nbsp;&nbsp;Bayar&nbsp;&nbsp;&nbsp;</button>
                    @endif
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

<div class="modal fade" data-keyboard="false" id="konfirm">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><b>Konfirmasi</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4><b>Apakah Pembayaran Sudah Sesuai?</b></h4>
        <p class="modal-text">Silahkan cek uang di laci, Jika uang untuk kembalian sudah mencukupi, Lanjutkan Cetak</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-danger mt-2" data-dismiss="modal">Batalkan</button>
        <button type="button" id="prosesOrder" class="btn btn-primary mt-2">Cetak</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" data-keyboard="false" id="withoutPrint">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel"><b style="color: #e7515a;">Printer Tidak Terhubung</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4><b>Apakah anda ingin melanjutkan pembayaran?</b></h4>
        <p class="modal-text">Data akan tersimpan tanpa cetak, sebelum melanjutkan, cek kembalian dahulu</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button class="btn btn-danger mt-2" data-dismiss="modal">Batalkan</button>
        <button type="button" id="buttOut" class="btn btn-primary mt-2">Simpan</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js-form')
<script>
  let payAndchange = function()
    {
      let sPrice = $("#startPrice").val();
      let price = $("#afterPrice").val();   
      let pay = $('#bayar').val();
      let diskon = $("#diskon").val();
      let change = + Number(diskon) + (Number(pay) - Number(sPrice))

      if(Number(diskon) >= Number(sPrice) || Number(change) < 0){
        $('#kembalian').html("Kembalian : <b class='float-right'>0</b>");
        $('#drawer').attr('disabled', true);
      }else if(Number(change) >= 0){
        $("#kembalian").html("Kembalian : <b class='float-right'>"+formatter.format(change)+'</b>');
        $('#drawer').removeAttr('disabled');
      } else {
        $('#kembalian').html("Kembalian : <b class='float-right'>0</b>");
        $('#drawer').attr('disabled', true);
      }
    }

    let disChange = function()
    {
      let sPrice = $("#startPrice").val();
      let diskon = $("#diskon").val();
      let discPrice = Number(sPrice) - Number(diskon)

      if(Number(sPrice)<Number(diskon)){
        $("#price").html("Total : <b class='float-right'>Error</b>");
        $("#afterPrice").val(Number(sPrice));
        $('#drawer').attr('disabled', true);
      }else if(Number(diskon)){
        $("#price").html("Total : <b class='float-right'>"+formatter.format(discPrice)+"</b><h6><i class='float-right' style='color:#acb0c3'><s>"+formatter.format(sPrice)+"</s></i></h6>");
        $("#afterPrice").val(Number(discPrice));
      } else {
        $("#price").html("Total : <b class='float-right'>"+formatter.format(sPrice)+'</b>');
        $("#afterPrice").val(Number(sPrice));
      }
      $('#cekDisc').change(function() { 
        if (!this.checked) {
          $("#price").html("Total : <b class='float-right'>"+formatter.format(sPrice)+'</b>');
          $("#afterPrice").val(Number(sPrice));
          $('#diskon').val(null)
        }
      })  
      console.log(sPrice)  
    }


  $(document).ready(function (){
    $('#cekDisc').change(function() { 
      if (this.checked) {
        $('#diskon').removeClass('d-none');
        $('#diskon').focus()
      } else {
        $('#diskon').addClass('d-none');
        disChange();
        $('#bayar').focus()
      }
    });
    //hotkey
      Mousetrap.bind('enter', function() {
        var price = $("#afterPrice").val();
        var pay = $('#bayar').val();
        if(Number(pay) == -1){
          alert('Pesanan Belum selesai')
        }else if(Number(pay) == 0){
          alert('Masukkan jumlah uang')
        }else if(Number(pay) < Number(price)){
          alert('Jumlah Uang tidak mencukupi')
        }else{
          $('#drawer').trigger('click')
        }
      });
      Mousetrap.bind('/', function() {
        $('#cekDisc').trigger('click');
      });
    //endhotkey
    //hotkeymodal
        $('#withoutPrint').on('shown.bs.modal', function() { 
          Mousetrap.bind('backspace', function(){
            $('#withoutPrint').modal('hide')
          });
          Mousetrap.bind('enter', function() {
            $('#buttOut').trigger('click')
          })
          $('#buttOut').focus()
        });

        $('#konfirm').on('shown.bs.modal', function() { 
          Mousetrap.bind('backspace', function(){
            $('#konfirm').modal('hide')
          });
          Mousetrap.bind('enter', function() {
            $('#prosesOrder').trigger('click')
          })
          $('#prosesOrder').focus()
        });

        $(window).on('hidden.bs.modal', function() { 
          Mousetrap.unbind('backspace')
            $('#bayar').focus()
            Mousetrap.bind('enter', function() {
              var price = $("#afterPrice").val();
              var pay = $('#bayar').val();
              if(Number(pay) == 0){
                alert('Masukkan jumlah uang')
              }else if(Number(pay) < Number(price)){
                alert('Jumlah Uang tidak mencukupi')
              }else{
                $('#drawer').trigger('click')
              }
            });
        });
    //endmodalkey

    //Cetak

    $('#drawer').on('click', function () {
      var price = $("#afterPrice").val();
      var pay = $('#bayar').val();
      var change = Number(pay) - Number(price)
      Swal.fire('Sedang Diproses')
      Swal.showLoading()
      if(change == 0){
        $.ajax({
        url: "{{url('/open/drawer') }}",
        type: "post",
        success: function(result){
          //console.log(result);
          var msg = result.messages[0];
          if(result.status == 'success'){
            $('#orderMenuForm').submit();
          }else{
            $('#withoutPrint').modal('show');
          }      
          Swal.hideLoading()  
          Swal.clickConfirm()
        },
        error:function(error){
          Swal.hideLoading()
          Swal.clickConfirm()
        }
        })
      }else{
        $.ajax({
        url: "{{url('/open/drawer') }}",
        type: "post",
        success: function(result){
          //console.log(result);
          var msg = result.messages[0];
          if(result.status == 'success'){
            $('#konfirm').modal('show');
          }else{
            $('#withoutPrint').modal('show');
          }
          Swal.hideLoading()
          Swal.clickConfirm()
        },
        error:function(error){
          Swal.hideLoading()
          Swal.clickConfirm()
        }
        })
      }
      
    });

    $('#print').on('click', function(){
      $('#miniform').submit();
    })

    $('#buttOut').on('click', function(){
      $('#orderMenuForm').submit();
    })
    // $('#bayar').setupMask(0);

    $('#bayar').on('keyup',function(){
      payAndchange();
      disChange();
    });
    $('#diskon').on('keyup',function(){
      payAndchange();
      disChange();
    });

    $('#prosesOrder').on('click', function(){
      $('#orderMenuForm').submit();
    })

    //disable enter form
    $('#orderMenuForm').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
  });
    //e


    let grid = $('#grid').DataTable({
      ajax: {
        url: "{{ url('order/detail/grid').'/' }}" + '{{$data->id}}',
        dataSrc: ''
      },
      dom: '<"row"<"col-md-12"<"row" > ><"col-md-12"rt> <"col-md-12"<"row">> >',

      "paging": false,
      "ordering": false,
      columns: [
        {
          data: null,
          render: function(data, type, full, meta){
            let prm = data.odpromoid
             ? '&nbsp;<span class="badge outline-badge-info"> Promo </span>'
             : '';

            return data.odmenutext + prm;
          }
        },
        { 
          data: 'odqty',
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            let promo = Number(data.odispromo) ? data.odpriceraw : data.odprice ;
            return formatter.format(promo);
          }
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            let promo = Number(data.odispromo) ? '@' + formatter.format(data.promodiscount) : '-' ;
            return promo;
          }
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            // let totalPromo = odispromo ? data. : ;
            return formatter.format(data.odtotalprice);
          }
        },
        { 
          data: 'odremark',
        },
        @if(!$data->orderpaid)
        { 
          data: 'oddelivertext',
        }
        @endif
      ]
    });

    
    const toast = swal.mixin({
      toast: true,
      position: 'center',
      showConfirmButton: false,
      timer: 2000,
      padding: '2em'
    });
  

    inputSearch('#cariMeja', "{{ Url('/meja/cariTersedia') }}", 'resolve', function(item) {
      return {
        text: item.text,
        id: item.id
      }
    });

    $('#cariMeja').on('select2:select', function (e) {
      $('#cariMeja').attr('data-has-changed', '1');
    });

    $('#orderType').on('change',function(){
      let val = $(this).val();
      if(val == "TAKEAWAY"){
        $('#divMeja').addClass('d-none')
      } else {
        $('#divMeja').removeClass('d-none')
      }
    });

    // Loop over them and prevent submission
    let validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  })
</script>
@endsection