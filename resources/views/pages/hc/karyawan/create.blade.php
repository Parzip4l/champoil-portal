@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
@php 
    $dataLogin = json_decode(Auth::user()->permission);
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
@endphp 
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
        <div class="card custom-card2">
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
                        <div class="col">
                            <label class="form-label">Divisi</label>
                            <select class=" form-select" data-width="100%" name="divisi">
                                @foreach($divisi as $divisi)
                                    <option value="{{$divisi->name}}">{{$divisi->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Jabatan</label>
                            <select class="form-select" data-width="100%" name="jabatan">
                                @foreach($jabatan as $jabatan)
                                    <option value="{{$jabatan->name}}">{{$jabatan->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Atasan</label>
                            <select class="form-select" data-width="100%" name="manager">
                                @foreach($atasan as $dataAtasan)
                                    <option value="{{$dataAtasan->nama}}">{{$dataAtasan->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Agama</label>
                            <select class=" form-select" data-width="100%" name="agama">
                                <option value="Islam">Islam</option>
                                <option value="Kristen">Kristen</option>
                                <option value="Katholik">Katholik</option>
                                <option value="Budha">Budha</option>
                                <option value="Hindu">Hindu</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class=" form-select" data-width="100%" name="jenis_kelamin">
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
                        <div class="col">
                            <label for="kode_karyawan" class="form-label">Slack ID</label>
                            <input id="slack_id" class="form-control" name="slack_id" type="text" required>
                        </div>
                        <div class="col">
                            <label for="kode_karyawan" class="form-label">Nomor Telepon</label>
                            <input id="telepon" class="form-control" name="telepon" type="number" placeholder="+62862612">
                        </div>
                        <div class="col">
                            <label for="kode_karyawan" class="form-label">Nomor Telepon Darurat</label>
                            <input id="telepon" class="form-control" name="telepon_darurat" type="number" placeholder="+62862612">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Status Kontrak</label>
                            <select class=" form-select" data-width="100%" name="status_kontrak">
                                <option value="Contract">Kontrak</option>
                                <option value="Permanent">Tetap</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Organisasi</label>
                            <select class=" form-select" data-width="100%" name="organisasi">
                                @foreach($organisasi as $organisasi)
                                    <option value="{{$organisasi->name}}">{{$organisasi->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Golongan</label>
                            <select class="form-select" data-width="100%" name="level">
                                @foreach($golongan as $dataGolongan)
                                    <option value="{{$dataGolongan->name}}">{{$dataGolongan->name}}</option>
                                @endforeach
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
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Full Address Domisili</label>
                            <textarea name="alamat" id="" cols="30" rows="10" class=form-control></textarea>
                        </div>
                        <div class="col">
                            <label class="form-label">Full Address KTP</label>
                            <textarea name="alamat_ktp" id="" cols="30" rows="10" class=form-control></textarea>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Pendidikan Terakhir</label>
                            <select class=" form-select" data-width="100%" name="pendidikan_terakhir" required>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                                <option value="DIPLOMA">DIPLOMA</option>
                                <option value="SARJANA">SARJANA</option>
                                <option value="MAGISTER">MAGISTER</option>
                                <option value="DOKTOR">DOKTOR</option>
                                <option value="OTHERS">OTHERS</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" class="form-control" required>
                        </div>
                    </div>
                    @if($employee->unit_bisnis === 'Kas')
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Sertifikasi</label>
                            <select class="form-select" data-width="100%" name="sertifikasi" required>
                                <option value="TIDAK ADA">TIDAK ADA</option>
                                <option value="GADA PRATAMA">GADA PRATAMA</option>
                                <option value="GADA MADYA">GADA MADYA</option>
                                <option value="GADA UTAMA">GADA UTAMA</option>
                                <option value="LAINNYA">LAINNYA</option>
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Expired Date</label>
                            <input type="date" name="expired_sertifikasi" class="form-control" required>
                        </div>
                    </div>
                    @endif
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Status Pernikahan</label>
                            <select class=" form-select" data-width="100%" name="status_pernikahan">
                                <option value="Married">Married</option>
                                <option value="Single">Single</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
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
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Status Pajak</label>
                            <select name="tax_code" class="form-control" id="">
                                <option value="45363">TER A</option>
                                <option value="45464">TER B</option>
                                <option value="45565">TER C</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" name="gambar">
                        </div>
                    </div>
                    <div class="card-header mb-3">
                        <h5>Payroll Info</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">BPJS Kesehatan</label>
                            <input type="number" class="form-control" name="bpjs_kes" placeholder="0902xxx" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">BPJS Ketenagakerjaan</label>
                            <input type="number" class="form-control" name="bpjs_tk" placeholder="0902xxx" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NPWP</label>
                            <input type="number" class="form-control" name="npwp" placeholder="0902xxx" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="BCA" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Number</label>
                            <input type="number" class="form-control" name="bank_number" required placeholder="89120xxx">
                        </div>
                    </div>
                    <div class="card-header mb-3">
                        <h5>User Login Info</h5>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="passwordInput" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Password" required>
                        <div class="text-danger" id="passwordError" style="display: none;"></div>
                        <div class="text-success" id="passwordSuccess" style="display: none;"></div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="passwordConfirmationInput" name="password_confirmation" placeholder="Konfirmasi Password" required>
                        <div class="text-danger" id="passwordConfirmationError" style="display: none;"></div>
                        <div class="text-success" id="passwordConfirmationSuccess" style="display: none;"></div>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="authCheck">
                        <label class="form-check-label" for="authCheck">
                            Show Password
                        </label>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Permission</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="dashboard_access">
                                    <label class="form-check-label">User</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="hr_access">
                                    <label class="form-check-label">HR</label>
                                </div>
                            </div>
                           
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="am_access">
                                    <label class="form-check-label">Area Manager</label>
                                </div>
                            </div>
                            @if($employee->unit_bisnis === 'Kas')
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="pic_access">
                                    <label class="form-check-label">Project PIC</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" id="client" class="form-check-input" name="permissions[]" value="client_access">
                                    <label class="form-check-label">Client</label>
                                </div>
                            </div>
                            
                            @endif
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="superadmin_access">
                                    <label class="form-check-label">Super Admin</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-2">
                        <div class="col-md-4" id="project_id" style="display:none">
                            <div class="form-check">
                                <label class="form-check-label">Project</label>
                                <select name="project_id" class="form-control select2">
                                    <option value="">-- Select Project -- </option>
                                    @if($project)
                                        @foreach($project as $pr)
                                            <option value="{{ $pr->id }}">{{ $pr->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary button-biru w-100" type="submit">Submit</button>
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
  <script src="{{ asset('assets/js/password.js') }}"></script>
  <script>
    const authCheck = document.getElementById('authCheck');
    const passwordInput = document.getElementById('passwordInput');
    const passwordConfirmationInput = document.getElementById('passwordConfirmationInput');

    $(document).ready(function(){
        $("#client").change(function(){
            if(this.checked) {
                // Checkbox is checked
                $('#project_id').show();
            } else {
                // Checkbox is unchecked
                $('#project_id').hide();
            }
        });
        
        $('#client').select2();
    });
    authCheck.addEventListener('change', function() {
        if (authCheck.checked) {
            passwordInput.type = 'text';
            passwordConfirmationInput.type = 'text';
        } else {
            passwordInput.type = 'password';
            passwordConfirmationInput.type = 'password';
        }
    });
</script>
@endpush