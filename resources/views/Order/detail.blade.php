@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Order</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/order').'/'.$data->id }}">Order</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Proses' : 'Ubah'}} Menu</a></li>
  </ol>
@endsection

@section('content-form')
<div class="widget-content widget-content-area br-6">
  <div class="row">
    <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
      <div class="statbox">
        <div class="widget-content">
          <form class="needs-validation" method="post" novalidate>
            <div class="form-row">
              <div class="col-md-12 mb-2">
                <label for="ordercustnametext">Nomor Pesanan</label>
                <input type="text" class="form-control" name="orderinvoice" value="{{ old('orderinvoice', $data->orderinvoice) }}" readonly>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-12 mb-2">
                <label for="ordercustnametext">Nama Pelanggan</label>
                <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
                <input type="text" class="form-control" name="ordercustnametext" value="{{ old('order', $data->ordercustnametext) }}" placeholder="Nama Pelanggan" {{ !empty($data->id) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-6 mb-2">
                <label for="ordertype">Tipe Pesanan</label>
                <input type="text" class="form-control" value="{{ old('ordertype', $data->ordertype) }}" disabled>
              </div>
              <div id="divMeja" class="col-md-6 mb-4">
                <label for="orderboardid">Nomor Meja</label>
                <input type="text" class="form-control" value="{{ old('orderboardtext', $data->orderboardtext) }}" placeholder="Nama Pelanggan" {{ !empty($data->id) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Detail Pesanan</h4>
                </div>                                                                        
              </div>
              <div class="table-responsive">
                <table id=grid class="table table-bordered mb-4">
                    <thead>
                      <th></th>
                      <th>Menu</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Total</th>
                      <th>Catatan</th>
                      <th>Status Pesanan</th>
                      <th><input title="Pilih Semua" type="checkbox" id="checkall"></th>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <th colspan="6" class="text-lg-right"><h3>Total :</h3></th>
                      <th colspan="2" class="text-lg-right"><h3> {{ number_format($data->orderprice,0) }}</h3></th>
                    <tfoot>
                </table>
              </div>
            </div>
          </form>
          <div class="float-right" style="padding-bottom:10px">
            <button id="deliver" disabled class="btn btn-info mt-2">menu selesai</button>
          </div>
        </div>
        <div class="row fixed-bottom">
          <div class="col-sm-12 ">
            <div class="widget-content widget-content-area" style="padding:10px">
              <div class="float-right">
                <a href="{{ url('/order').'/'.$data->id }}" type="button" id="headerOrder" class="btn btn-success mt-2">Ubah Pesanan</a>
                <a type="button" id="prosesOrder" class="btn btn-primary mt-2">Pembayaran</a>
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


  $(document).ready(function (){


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
            data: 'id',
            visible : false
          },
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
          },
          { 
            data:null,
            render: function(data, type, full, meta){
              if(data.oddelivertext == 'Sedang Diproses'){
                return '<input type="checkbox" name="customCheck1" class="customCheck1" value="' + data.id+ '" >';
              }else{
                return'<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8dbf42" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check"><polyline points="20 6 9 17 4 12"></polyline></svg>';
              }
            }
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
  
    let idSub = [];
  
    $('#grid').on('change', 'input.customCheck1', function(){
      let cek = $(this).is(':checked') ? true : false;
      var input = $(this).closest('tr').find('.customCheck1').val();

      if(cek){ 
        idSub.push(input);
      }else{
        idSub = idSub.filter(item => item !== input)
      }
      
      if(idSub.length >= 1){
        $('#deliver').removeAttr('disabled');
      }else{
        $('#deliver').attr('disabled', true);
      }
      console.log(idSub)
    });

    $("#checkall").on('change', function(){
      idSub = [];
      let cek = $(this).is(':checked') ? true : false; 
      $('.customCheck1').prop('checked', this.checked);
      if(cek){
        $("input:checkbox[name=customCheck1]:checked").each(function(){
        idSub.push($(this).val());
        });
      }else{
        idSub = [];
        $('#deliver').attr('disabled', true);
      }
      if(idSub.length >= 1){
        $('#deliver').removeAttr('disabled');
      }
      console.log(idSub)
    })

    $('#deliver').on('click',function(){
      if(idSub.length >= 1){
        $.ajax({
          url: "{{ url('order/delivered') . '/'}}" + '{{$data -> id}}',
          type: "post",
          data: { idsub: idSub },
          success: function(result){
            console.log(result);
            var msg = result.messages[0];
            if(result.status == 'success'){
              toast({
                type: 'success',
                title: msg,
                padding: '2em',
              })
              grid.ajax.reload(null, true);
              $('#deliver').attr('disabled', true);
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
      }else{
        toast({
          type: 'error',
          title: 'Terjadi Masalah',
          padding: '2em',
        })
      }
    })

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