@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Shift</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/shift') }}">Shift</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Shift</a></li>
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
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Shift</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/shift') }}/{{ empty($data->id) ? 'simpan' : 'edit'}}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-6 mb-5">
                <label for="name">Shift</label>
                <input readonly type="text" name="shiftindex" value="{{$data->shiftindex}}" class="form-control" id="name" placeholder="Nama" required>
              </div><div class="col-md-6 mb-5">
              </div>
              <div class="col-md-6 mb-5">
                <label>Tanggal</label>
                <input id="get"  class="form-control flatpickr flatpickr-input">            
              </div>
              <div class="col-md-6 mb-5">
                <label for="name">Karyawan</label>
                <input readonly type="text" name="shiftuserid" value="{{ session('username') }}" class="form-control" id="name" placeholder="Nama" required>
              </div>
              <div class="col-md-6 mb-5">
                <label for="price">Uang Kertas</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                  </div>
                  <input name="shiftstartcash" value="{{ old('shiftstartcash', $data->shiftstartcash) }}" class="form-control rupiah" id="pricing" placeholder="Harga" required>
                </div>
              </div>
              <div class="col-md-6 mb-5">
                <label for="price">Uang Koin</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                  </div>
                  <input name="shiftstartcoin" value="{{ old('shiftstartcoin', $data->shiftstartcoin) }}" class="form-control rupiah" id="pricing" placeholder="Harga">
                </div>
              </div>             
            </div>  
              <div class="float-right">
                <a href="{{ url('/shift') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
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
    let f1 = flatpickr($('#get'), {
    altinput: true,
    altformat: "d-m-Y",
    noCalendar: true,
    dateFormat: "d-m-Y",
    defaultDate: "today"
    });
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    let forms = document.getElementsByClassName('needs-validation');

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