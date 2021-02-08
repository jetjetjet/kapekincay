@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Meja</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/meja') }}">Meja</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Meja</a></li>
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
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Meja</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/meja/simpan') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-6 mb-5">
                <label for="number">Nomor Meja</label>
                <input type="text" name="boardnumber" value="{{ old('boardnumber', $data->boardnumber) }}" class="form-control" id="number" placeholder="Nomor Meja" required {{ $data->id == null ? '' : 'readonly' }}>
              </div>
              <div class="col-md-6 mb-5">
                  <label for="floor">Lantai</label>
                  <input type="text" name="boardfloor" value="{{ old('boardfloor', $data->boardfloor) }}" class="form-control" id="floor" placeholder="Lantai" required>
              </div>
              <!-- <div class="col-md-4 mb-5">
                <label for="validationTooltipUsername">Username</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="validationTooltipUsernamePrepend">@</span>
                  </div>
                  <input type="text" class="form-control" id="validationTooltipUsername" placeholder="Username" aria-describedby="validationTooltipUsernamePrepend" required>
                  <div class="invalid-tooltip">
                    Please choose a unique and valid username.
                  </div>
                </div>
              </div> -->
            </div>
            <div class="form-row">
              <div class="col-md-12 mb-5">
                <label for="space">Kapasitas Meja</label>
                <div class="input-group mb-4">
                  <input type="text" name="boardspace" value="{{ old('boardspace', $data->boardspace) }}" id="space" class="form-control" placeholder="Kapasitas Meja">
                  <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon6">Orang</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="float-right">
              <a href="{{ url('/meja') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
              <button class="btn btn-primary mt-2" id="sub" type="submit">Simpan</button>
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
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }else if (form.checkValidity() === true){
        $('#sub').attr('disabled', true);
        }
        form.classList.add('was-validated');
      }, false);
    });
  })
</script>
@endsection