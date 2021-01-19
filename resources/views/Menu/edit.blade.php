@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Menu</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="javascript:void(0);">Menu</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">Tambah</a></li>
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
              <h4>Tambah Menu</h4>
            </div>                                                                        
          </div>
        </div>
        <div class="widget-content widget-content-area">
          <form class="needs-validation" method="post" novalidate action="{{ url('/menu/simpan') }}">
            <div class="form-row">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="id" name="id" value="{{ old('id', $data->id) }}" />
              <div class="col-md-12 mb-5">
                <label for="name">Nama</label>
                <input type="text" name="menuname" value="{{ old('menuname', $data->menuname) }}" class="form-control" id="name" placeholder="Nama" required>
              </div>
              <div class="col-md-6 mb-5">
                  <label for="type">Kategori</label>
                  <select class="form-control" id="type" name="menutype">
                  <option value="Makanan" {{ old('menutype', $data->menutype) == 'Makanan' ? ' selected' : '' }}> Makanan</option>
                  <option value="Minuman" {{ old('menutype', $data->menutype) == 'Minuman' ? ' selected' : '' }}> Minuman</option>
                  <option value="Paket" {{ old('menutype', $data->menutype) == 'Paket' ? ' selected' : '' }}> Paket</option>
                </select>
              </div>
              <div class="col-md-6 mb-5">
                  <label for="price">Harga</label>
                  <input name="menuprice" value="{{ old('menuprice', $data->menuprice) }}" class="form-control" id="pricing" placeholder="Harga" required>
            </div> 
            <div class="col-md-12 mb-5">
                <label for="detail">Detail Menu</label>
                <textarea name="menudetail" rows="3" class="form-control" id="detail" placeholder="Detail Menu" >{{ old('menudetail', $data->menudetail) }}
                </textarea>
            </div>
            <div class="col-md-6 mb-5">
            <input type="file" class="custom-file-input" id="menuimg">
            <label class="custom-file-label" for="menuimg">Pilih Gambar</label>
            </div>
            <div class="widget-content widget-content-area">
                                    <label for="price">Rp.999,9999,999.99</label>
                                    <input type="text" class="form-control mb-4" id="pricing">
            </div>
            <div class="float-right">
              <a href="{{ url('/menu') }}" type="button" class="btn btn-danger mt-2" type="submit">Batal</a>
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
    $("#pricing").inputmask({mask:"Rp.999.999.999"});
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
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