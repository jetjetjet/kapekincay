@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>User</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/user') }}">User</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} User</a></li>
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
              <h4>{{ empty($data->id) ? 'Tambah' : 'Ubah'}} User</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/user/simpan') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-6 mb-4">
                <label for="username">Username</label>
                <input type="text" name="username" value="{{ old('username', $data->username) }}" class="form-control" id="username" placeholder="Username" {{ !empty($data->id) ? 'readonly' : '' }} required>
              </div>
              <div class="col-md-6 mb-4">
                <label for="userfullname">Nama Lengkap</label>
                <input type="text" name="userfullname" value="{{ old('userfullname', $data->userfullname) }}" class="form-control" id="userfullname" placeholder="Nama Lengkap">
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-6 mb-4">
                <label for="usercontact">Telp/Hp</label>
                <input type="text" name="usercontact" value="{{ old('usercontact', $data->usercontact) }}" class="form-control" id="usercontact" placeholder="Kontak Telp/Hp">
              </div>
              <div class="col-md-6 mb-4">
                <label for="userjoindate">Tanggal Awal Kerja</label>
                <input type="text" name="userjoindate" value="{{ old('userjoindate', $data->userjoindate) }}" class="form-control  flatpickr flatpickr-input active" id="userjoindate" placeholder="Tanggal Awal Kerja Karyawan">
              </div>
              <div class="col-md-12 mb-4">
                <label for="useraddress">Alamat</label>
                <textarea class="form-control" name="useraddress" >{{ old('useraddress', $data->useraddress) }}</textarea>
              </div>
            </div>
            @if(empty($data->id))
            <div class="form-row">
              <div class="col-md-12 mb-4">
                <label for="username"><b>Password</b></label>
                <input type="text" name="userpassword" value="{{ old('userpassword', $data->userpassword) }}" class="form-control" id="userpassword" placeholder="Password" {{ empty($data->id) ? 'required' : '' }}>
                <div class="invalid-tooltip">
                  Password tidak sama!
                </div>
              </div>
            </div>
            @endif
            <div class="float-left">
            </div>
            <div class="float-right">
              <a href="{{ url('/user') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
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
    let f1 = flatpickr($('#userjoindate'));

    $('.select2').select2({
      tags: true,
      placeholder: 'Pilih',
      searchInputPlaceholder: 'Search options'
    });

    @if(empty($data->id))
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
    @endif
  })
</script>
@endsection