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
<div class="widget-content widget-content-area br-6">
  <div class="row">
    <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
      <div class="statbox">
        <div class="widget-content">
            <div class="form-row">
              <div class='col-md-12'>
                <h4>Nomor Pesanan : <b>{{$data->orderinvoice}}</b></h4>
              </div>
              <div class='col-md-12'>
              @if($data->ordertype == 'DINEIN')
              <h4>Tipe Pesanan : {{ $data->ordertypetext }}</h4>
              @else
              <h4>Tipe Pesanan : <b style="color:#1b55e2">Bungkus</b> </h4>
              @endif
              </div>
              <div class='col-md-12 mb-2'>
                @if($data->ordertype == 'DINEIN')
                <h4 style="color:#1b55e2"><b>{{$data->orderboardtext}}</b></h4>
                @endif
              </div>
              <div class="col-md-12">
                  <h6>Detail Pesanan</h6>
                </div>   
            </div>
            <div class="form-row">
              <div class="table-responsive">
                <table id=grid class="table table-bordered mb-20">
                    <thead>
                      <th>Menu</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Total</th>
                      <th>Catatan</th>
                      <th>Status Pesanan</th>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
                <form id="orderMenuForm" method="post" novalidate action="{{url('/order/bayar')}}/{{$data->id}}">
                  <div class="col-md-12">
                    <div class="text-right float-right">
                      <input type="hidden" id="total" value="{{$data->orderprice}}">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                      <input type="hidden" name="username" id="name" value="{{ session('username') }}" />
                        <h3>Total :<b> {{ number_format($data->orderprice,0) }}</b></h3>
                        @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')
                        
                        @elseif($data->getstat == null && $data->orderstatus == 'COMPLETED' || $data->ordertype == 'TAKEAWAY')
                          <input autofocus type="number" class="form-control text-right mousetrap" required name="orderpaidprice" id="bayar" placeholder="Jumlah Uang">
                          <h3 id="kembalian">Kembalian :</h3>                       
                        @else      
                        <input type="hidden" id="bayar" value="-1" />
                        @endif          
                    </div>
                    <div class="form-group col-md-3">
                      @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')                                           
                        <h4>Status Pesanan : <b>{{$data->orderstatuscase}}</b></h4>                     
                      @elseif($data->orderstatus == 'COMPLETED' && $data->getstat == null || $data->ordertype == 'TAKEAWAY')
                        <h4>Jenis Pembayaran</h4>
                        <select class="form-control mousetrap" id="type" name="orderpaymentmethod">
                          <option value="Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Tunai' ? ' selected' : '' }}> Tunai</option>
                          <option value="Non-Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Non-Tunai' ? ' selected' : '' }}> Non-Tunai</option>
                        </select>                     
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
        </div>
        <div id="fixbot" class="row fixed-bottom">
          <div class="col-sm-12 ">
            <div class="widget-content widget-content-area" style="padding:10px">
              <div class="float-right">
                <a href="{{url('/order/meja/view')}}" id="back" type="button" class="btn btn-warning mt-2">Kembali</a>
                @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')
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
  var payAndchange = function()
    {
      var price = $("#total").val();
      var pay = $('#bayar').val();
      var change = Number(pay) - Number(price)
      if(Number(pay) >= Number(price)){
        $("#kembalian").html('Kembalian : <b>'+formatter.format(change)+'</b>');
        $('#drawer').removeAttr('disabled');
      } else {
        $('#kembalian').html('Kembalian : <b>0</b>');
        $('#drawer').attr('disabled', true);
      }
      //console.log(price, pay, change )
    }


  $(document).ready(function (){
    //hotkey
      Mousetrap.bind('enter', function() {
        var price = $("#total").val();
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
              var price = $("#total").val();
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
      var price = $("#total").val();
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
          data: 'odmenutext',
        },
        { 
          data: 'odqty',
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            return formatter.format(data.odprice);
          }
        },
        { 
          data: null,
          render: function(data, type, full, meta){
            return formatter.format(data.odtotalprice);
          }
        },
        { 
          data: 'odremark',
        },
        { 
          data: 'oddelivertext',
        }
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