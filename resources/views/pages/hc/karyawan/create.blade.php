@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Tambah Karyawan</li>
  </ol>
</nav>
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
  @endif

  @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Form Tambah Data Karyawan</h4>
        <form method="POST" action="{{ route('employee.store') }}" enctype="multipart/form-data">
            @csrf
          <div class="row mb-3">
            <div class="col">
                <label for="name" class="form-label">Nama lengkap</label>
                <input id="name" class="form-control" name="nama" type="text" placeholder="John Doe">
            </div>
            <div class="col-md-6">
                <label for="Ktp" class="form-label">KTP</label>
                <input id="ktp" class="form-control" name="ktp" type="number" placeholder="3xxxxxx">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label for="kode_karyawan" class="form-label">Kode Karyawan</label>
                <input id="kode_karyawan" class="form-control" name="nik" type="number" placeholder="xxx-xxx-xxx">
            </div>
            <div class="col-md-6">
                <label class="form-label">Jabatan</label>
                <select class="js-example-basic-single form-select" data-width="100%" name="jabatan">
                    @foreach($jabatan as $jabatan)
                        <option value="{{$jabatan->name}}">{{$jabatan->name}}</option>
                     @endforeach
                </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label class="form-label">Agama</label>
                <select class="js-example-basic-single form-select" data-width="100%" name="agama">
                    <option value="Islam">Islam</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jenis Kelamin</label>
                <select class="js-example-basic-single form-select" data-width="100%" name="jenis_kelamin">
                    <option value="Laki-Laki">Laki-Laki</option>
                    <option value="Perempuan">Perempuan</option>
                </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label for="kode_karyawan" class="form-label">Email</label>
                <input id="email" class="form-control" name="email" type="email" placeholder="johndoe@champoil.co.id">
            </div>
            <div class="col-md-6">
                <label for="kode_karyawan" class="form-label">Nomor Telepon</label>
                <input id="telepon" class="form-control" name="telepon" type="number" placeholder="08xxxxxx">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label class="form-label">Status Kontrak</label>
                <select class="js-example-basic-single form-select" data-width="100%" name="status_kontrak">
                    <option value="Contract">Kontrak</option>
                    <option value="Permanent">Tetap</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Organisasi</label>
                <select class="js-example-basic-single form-select" data-width="100%" name="organisasi">
                    <option value="Frontline Officer">Frontline Officer</option>
                    <option value="Management Leaders">Management Leaders</option>
                </select>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label class="form-label">Tanggal Masuk</label>
                <input type="date" class="form-control" name="joindate">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Berakhir</label>
                <input type="date" class="form-control" name="berakhirkontrak">
            </div>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label class="form-label">Tempat Lahir</label>
                <input type="text" class="form-control" name="tempat_lahir" placeholder="Jakarta">
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" name="tanggal_lahir">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">alamat</label>
            <textarea name="alamat" id="" cols="30" rows="10" class=form-control></textarea>
          </div>
          <div class="row mb-3">
            <div class="col">
                <label class="form-label">Status Pernikahan</label>
                <select class="js-example-basic-single form-select" data-width="100%" name="status_pernikahan">
                    <option value="Married">Married</option>
                    <option value="Single">Single</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Jumlah Tanggungan</label>
                <select name="tanggungan" class="form-control" id="">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Photo</label>
                <input type="file" class="form-control" name="gambar">
            </div>
          </div>
          <button class="btn btn-primary" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/typeahead-js/typeahead.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropzone/dropzone.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/dropify/js/dropify.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/pickr/pickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script src="{{ asset('assets/js/typeahead.js') }}"></script>
  <script src="{{ asset('assets/js/tags-input.js') }}"></script>
  <script src="{{ asset('assets/js/dropzone.js') }}"></script>
  <script src="{{ asset('assets/js/dropify.js') }}"></script>
  <script src="{{ asset('assets/js/pickr.js') }}"></script>
  <script src="{{ asset('assets/js/flatpickr.js') }}"></script>
  
@endpush