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
                <h4>Nomor Pesanan : <b>{{ old('orderinvoice', $data->orderinvoice) }}</b></h4>
              </div>
              <div class='col-md-12'>
                <h4 style="color:#1b55e2"><b>{{ old('orderboardtext', $data->orderboardtext) }}</b></h4>
              </div>
              <div class='col-md-12 mb-2'>
                <h4>Tipe Pesanan : {{ old('ordertype', $data->ordertype) }}</h4>
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
                <div class="col-md-12">
                  <div class="text-right float-right">
                    <input type="hidden" id="total" value="{{$data->orderprice}}">
                      <h3>Total :<b> {{ number_format($data->orderprice,0) }}</b></h3>
                      @if($data->getstat == '1' && $data->orderstatus == 'COMPLETED')
                        <input type="number" class="form-control text-right" required name="orderpaidprice" id="bayar" placeholder="Jumlah Uang">
                        <h3 id="kembalian">Kembalian :</h3>                       
                      @endif                
                  </div>
                  <div class="form-group col-md-3">
                    @if($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID')                                           
                      <h4>Status Order : <b>{{$data->orderstatus}}</b></h4>                     
                      @elseif($data->orderstatus == 'COMPLETED' && $data->getstat == '1')
                      <h4>Jenis Pembayaran</h4>
                      <select class="form-control" id="type" name="orderpaymentmethod">
                        <option value="Tunai" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Tunai' ? ' selected' : '' }}> Tunai</option>
                        <option value="Debit" {{ old('orderpaymentmethod', $data->orderpaymentmethod) == 'Debit' ? ' selected' : '' }}> Debit</option>
                      </select>
                      
                    @endif
                  </div>
                </div>
              </div>
            </div>
        </div>
        @if(!($data->orderstatus == 'VOIDED' || $data->orderstatus == 'PAID'))
        <div id="fixbot" class="row fixed-bottom">
          <div class="col-sm-12 ">
            <div class="widget-content widget-content-area" style="padding:10px">
              <div class="float-left">
                <a type="button" id="void" class="btn btn-danger mt-2">Batalkan Pesanan</a>
              </div>
              <div class="float-right">
                <a href="{{ url('/order').'/'.$data->id }}" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Pesanan</a>
                @if(Perm::can(['order_pembayaran']))
                  <button disabled id="prosesOrder" class="btn btn-primary mt-2">&nbsp;&nbsp;&nbsp;Bayar&nbsp;&nbsp;&nbsp;</button>
                @endif
              </div>
            </div>
          </div>
        </div>
        @endif
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
        $("#kembalian").html('Kembalian : <b>'+change+'</b>');
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

    $('#prosesOrder').on('click',function(){
      var id =$("#id").val();
      var paid = $('#bayar').val();
      var method = $('#type').val();
      $.ajax({
        url: "{{ url('/order/bayar') . '/' }}"+ '{{$data->id}}',
        type: "post",
        data: { id: id, orderpaidprice: paid, orderpaymentmethod: method },
        success: function(result){
          console.log(result);
          var msg = result.messages[0];
          if(result.status == 'success'){
            toast({
            type: 'success',
            title: msg,
            padding: '2em',
            })
            $('#fixbot').attr('style','visibility: hidden');
          }else{
            toast({
            type: 'error',
            title: msg,
            padding: '2em',
            })
          }
        },
        error:function(error){

        }
      });
    })

    $('#void').on('click', function (e) {
        e.preventDefault();
        
        const rowData = grid.row($(this).closest('tr')).data();
        const url = "{{ url('order/batal') . '/' }}" + '{{$data->id}}';
        const title = 'Batalkan Pesanan';
        const pesan = 'Alasan batal?'
        gridDeleteInputvoid(url, title, pesan, grid);
        $('#fixbot').attr('style','visibility: hidden');
      });

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