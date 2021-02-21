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
                <form id="orderMenuForm" method="post" novalidate action="{{ url('/order/bayar')}}/{{$data->id}}">
                  <div class="col-md-12">
                    <div class="text-right float-right">
                      <input type="hidden" id="total" value="{{$data->orderprice}}">
                      <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                        <h3>Total :<b> {{ number_format($data->orderprice,0) }}</b></h3>
                        @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')
                        @elseif($data->getstat == null && $data->orderstatus == 'COMPLETED' || $data->ordertype == 'TAKEAWAY')
                          <input type="number" class="form-control text-right" required name="orderpaidprice" id="bayar" placeholder="Jumlah Uang">
                          <h3 id="kembalian">Kembalian :</h3>                       
                        @endif                
                    </div>
                    <div class="form-group col-md-3">
                      @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')                                           
                        <h4>Status Pesanan : <b>{{$data->orderstatuscase}}</b></h4>                     
                      @elseif($data->orderstatus == 'COMPLETED' && $data->getstat == null || $data->ordertype == 'TAKEAWAY')
                        <h4>Jenis Pembayaran</h4>
                        <select class="form-control" id="type" name="orderpaymentmethod">
                          <option value="Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Tunai' ? ' selected' : '' }}> Tunai</option>
                          <option value="Debit" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Debit' ? ' selected' : '' }}> Debit</option>
                        </select>                     
                      @endif
                    </div>
                  </div>
                </form>
              </div>
            </div>
        </div>
        <div id="fixbot" class="row fixed-bottom">
          <div class="col-sm-12 ">
            <div class="widget-content widget-content-area" style="padding:10px">
              <div class="float-right">
                <a href="{{url('/order/meja/view')}}" type="button" class="btn btn-warning mt-2">Kembali</a>
                @if(!($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID'))
                  <a href="{{ url('/order').'/'.$data->id }}" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Pesanan</a>
                  @if(Perm::can(['order_pembayaran']))
                    <button disabled id="prosesOrder" class="btn btn-primary mt-2">&nbsp;&nbsp;&nbsp;Bayar&nbsp;&nbsp;&nbsp;</button>
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
        $('#prosesOrder').removeAttr('disabled');
      } else {
        $('#kembalian').html('Kembalian : <b>0</b>');
        $('#prosesOrder').attr('disabled', true);
      }
      console.log(price, pay, change )
    }
  $(document).ready(function (){
    $('[type=number]').setupMask(0);
    $('#bayar').on('keyup',function(){
      payAndchange();
    });

    $('#prosesOrder').on('click', function(){
      $('#prosesOrder').attr('dissabled');
      $('#orderMenuForm').submit();
    })


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
      timer: 3000,
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