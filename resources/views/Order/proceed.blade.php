@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Order</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Order</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Proses' : 'Ubah'}} Menu</a></li>
  </ol>
@endsection

@section('content-form')
  <div class="widget-content widget-content-area br-6">
    <div class="row">
      <div id="flStackForm" class="col-lg-12 layout-spacing layout-top-spacing">
        <div class="statbox widget box box-shadow">
        <div class="widget-header">                                
          <div class="row">
            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Menu</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{url('/order/save')}}">
            @if($data->orderinvoice != null)
              <div class="form-row">
                <div class="col-md-12 mb-2">
                  <label for="ordercustnametext">Nomor Pesanan</label>
                  <input type="text" class="form-control" name="orderinvoice" value="{{ old('orderinvoice', $data->orderinvoice) }}" readonly>
                </div>
              </div>
            @endif
            <div class="form-row">
              <div class="col-md-12 mb-2">
                <label for="ordercustnametext">Nama Pelanggan</label>
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
                <input type="text" class="form-control" name="ordercustnametext" value="{{ old('order', $data->ordercustnametext) }}" placeholder="Nama Pelanggan" {{ !empty($data->id) ? 'readonly' : '' }} required>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-6 mb-2">
                <label for="ordertype">Tipe Pesanan</label>
                <select id="orderType" class="form-control" name="ordertype">
                  <option value="DINEIN">Makan Ditempat</option>
                  <option value="TAKEAWAY">Bungkus</option>
                </select>
              </div>
              <div id="divMeja" class="col-md-6 mb-4">
                <label for="orderboardid">Nomor Meja</label>
                <select class="form-control form-control-sm" id="cariMeja" name="orderboardid">
                </select>
              </div>
            </div>
            <div class="form-row">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                  <h4>Detail Pesanan</h4>
                </div>                                                                        
              </div>
              <div class="table-responsive">
                <table class="table table-bordered mb-4">
                    <thead>
                      <th>Menu</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Catatan</th>
                      <th></th>
                    </thead>
                    <tbody>
                      @foreach($data->orderDetails as $key=>$row)
                        <tr>
                          <td>
                            <input type="hidden" name="dtl[{{$key}}][odmenutext]" value="{{$row['odmenutext']}}" />
                            {{$row['odmenutext']}}
                          </td>
                          <td>
                            <input type="hidden" name="dtl[{{$key}}][odqty]" value="{{$row['odqty']}}" />
                            {{$row['odqty']}}
                          </td>
                          <td>
                            <input type="hidden" name="dtl[{{$key}}][odprice]" value="{{$row['odprice']}}" />
                            {{$row['odprice']}}
                          </td>
                          <td>
                            <input type="hidden" name="dtl[{{$key}}][odremark]" value="{{$row['odremark']}}" />
                            {{$row['odremark']}}
                          </td>
                          <td>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
              </div>
            </div>
            <div class="float-right">
              <a id="cancelOrder" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              <button class="btn btn-primary mt-2" type="submit">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js-form')
<script>
  
  $(document).ready(function (){

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