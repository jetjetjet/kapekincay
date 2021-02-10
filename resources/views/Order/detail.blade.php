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
        <div class="widget-content widget-content-area">
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
                <table class="table table-bordered mb-4">
                    <thead>
                      <th>Menu</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Catatan</th>
                      <th></th>
                    </thead>
                    <tbody>
                      @foreach($data->subOrder as $key=>$row)
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
          </form>
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