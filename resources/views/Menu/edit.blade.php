@extends('Layout.layout-form')

@section('breadcumb')
  <div class="title">
    <h3>Menu</h3>
  </div>
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0);">Master Data</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/menu') }}">Menu</a></li>
    <li class="breadcrumb-item active"  aria-current="page"><a href="javascript:void(0);">{{ empty($data->id) ? 'Tambah' : 'Ubah'}} Menu</a></li>
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
          <form class="needs-validation" method="post" novalidate action="{{ url('/menu/simpan') }}" enctype="multipart/form-data">
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
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text" id="inputGroup-sizing-sm">Rp </span>
                  </div>
                  <input name="menuprice" value="{{ old('menuprice', $data->menuprice) }}" class="form-control rupiah" id="pricing" placeholder="Harga" required>
                </div>
              </div>
              <div class="col-md-12 mb-5">
              <label>Status Menu(Kosong, Ada)</label>
              <br>
              @if(isset($data->menuavaible))
              <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                <input type="checkbox" id="mav" name="menuavaible" {{ $data->menuavaible ? 'checked' : ''}}>
                <span class="slider round"></span>
                </label>
              @elseif(empty($data->menuavaible))
              <label class="switch s-icons s-outline  s-outline-success  mb-4 mr-2">
                <input type="checkbox" id="mav" name="menuavaible" checked>
                <span class="slider round"></span>
                </label>
                @endif
              </div> 
              <div class="col-md-12 mb-5">
                <label for="detail">Detail Menu</label>
                <textarea name="menudetail" rows="3" class="form-control" id="detail" placeholder="Detail Menu" >{{ old('menudetail', $data->menudetail) }}</textarea>
              </div>
              <div class="col-md-6 mb-5">
                <div class="custom-file-container" data-upload-id="myFirstImage">
                    <label>Gambar Menu <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                    <label class="custom-file-container__custom-file" >
                      <input name="menuimg" type="file" class="custom-file-input" id="menuimg" accept="image/*">
                      <span class="custom-file-container__custom-file__custom-file-control"></span>
                    </label>
                    <div class="custom-file-container__image-preview">
                    </div>
                </div>
              </div>   
              @if(isset($data->menuimg))
              <div class="col-md-6 mb-5">                
                <label for="img"><b>Gambar Menu Saat Ini</b></label>
                <br>
                <div class="n-chk">
                  <label class="new-control new-checkbox new-checkbox-rounded new-checkbox-text checkbox-danger">
                    <input type="checkbox" class="new-control-input" name="delimg" id ="delimg" value = "1">
                    <span class="new-control-indicator"></span><span class="new-chk-content">Hapus Foto</span>
                  </label>
                </div> 
                <br>
                <img src="{{ url('/').$data->menuimg}}"style="vertical-align:top"  class="imgrespo"  ></img>
                <input type="hidden" id="hidimg" name="hidimg" value="{{ old('menuimg', $data->menuimg) }}" />                
              </div>
              @endif
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
    var firstUpload = new FileUploadWithPreview('myFirstImage')
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