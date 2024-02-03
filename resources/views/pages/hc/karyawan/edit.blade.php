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
<nav class="page-breadcrumb desktop">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Karyawan</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit Karyawan</li>
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
<div class="row desktop">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card custom-card2">
      <div class="card-body">
        <h4 class="card-title">Edit Data karyawan</h4>
        <form method="POST" action="{{ route('employee.update', ['employee' => $employee->nik]) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col mb-3">
                    <label for="name" class="form-label">Nama lengkap</label>
                    <input id="name" class="form-control" name="nama" type="text" placeholder="John Doe" value="{{$employee->nama}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Ktp" class="form-label">KTP</label>
                    <input id="ktp" class="form-control" name="ktp" type="number" placeholder="3xxxxxx" value="{{$employee->ktp}}">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="kode_karyawan" class="form-label">Kode Karyawan</label>
                    <input id="kode_karyawan" class="form-control" name="nik" type="number" placeholder="xxx-xxx-xxx" value="{{$employee->nik}}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{$employee->jabatan}}">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Agama</label>
                    <input type="text" name="agama" value="{{$employee->agama}}" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="jenis_kelamin">
                        <option value="Laki-Laki" {{$employee->jenis_kelamin == 'Laki-Laki' ? 'selected' : ''}}>Laki-Laki</option>
                        <option value="Perempuan" {{$employee->jenis_kelamin == 'Perempuan' ? 'selected' : ''}}>Perempuan</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="kode_karyawan" class="form-label">Email</label>
                    <input id="email" class="form-control" name="email" type="email" placeholder="johndoe@champoil.co.id" value="{{$employee->email}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="kode_karyawan" class="form-label">Nomor Telepon</label>
                    <input id="telepon" class="form-control" name="telepon" type="number" placeholder="08xxxxxx" value="{{$employee->telepon}}">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Status Kontrak</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="status_kontrak">
                        <option value="Contract" {{$employee->status_kontrak == 'Contract' ? 'selected' : ''}}>Kontrak</option>
                        <option value="Permanent" {{$employee->status_kontrak == 'Permanent' ? 'selected' : ''}}>Tetap</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Organisasi</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="organisasi">
                        <option value="Professional Frontline" {{$employee->organisasi == 'Professional Frontline' ? 'selected' : ''}}>Professional Frontline</option>
                        <option value="Management Leaders" {{$employee->organisasi == 'Management Leaders' ? 'selected' : ''}}>Management Leaders</option>
                        <option value="Frontline Officer" {{$employee->organisasi == 'Frontline Officer' ? 'selected' : ''}}>Frontline Officer</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" name="joindate" value="{{$employee->joindate}}" >
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Berakhir</label>
                    <input type="date" class="form-control" name="berakhirkontrak" value="{{$employee->berakhirkontrak}}">
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Tempat Lahir</label>
                    <input type="text" class="form-control" name="tempat_lahir" placeholder="Jakarta" value="{{$employee->tempat_lahir}}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" name="tanggal_lahir" value="{{$employee->tanggal_lahir}}">
                </div>
            </div>
            <div class="mb-3 mb-3">
                <label class="form-label">alamat</label>
                <textarea name="alamat" id="" cols="30" rows="10" class=form-control>{{$employee->alamat}}</textarea>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label class="form-label">Status Pernikahan</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="status_pernikahan">
                        <option value="Married" {{$employee->status_pernikahan == 'Married' ? 'selected' : ''}}>Married</option>
                        <option value="Single" {{$employee->status_pernikahan == 'Single' ? 'selected' : ''}}>Single</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Jumlah Tanggungan</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="tanggungan">
                        <option value="0" {{$employee->tanggungan == '0' ? 'selected' : ''}}>0</option>
                        <option value="1" {{$employee->tanggungan == '1' ? 'selected' : ''}}>1</option>
                        <option value="2" {{$employee->tanggungan == '2' ? 'selected' : ''}}>2</option>
                        <option value="3" {{$employee->tanggungan == '3' ? 'selected' : ''}}>3</option>
                        <option value="4" {{$employee->tanggungan == '4' ? 'selected' : ''}}>4</option>
                        <option value="5" {{$employee->tanggungan == '5' ? 'selected' : ''}}>5</option>
                        <option value="6" {{$employee->tanggungan == '6' ? 'selected' : ''}}>6</option>
                        <option value="7" {{$employee->tanggungan == '7' ? 'selected' : ''}}>7</option>
                        <option value="8" {{$employee->tanggungan == '8' ? 'selected' : ''}}>8</option>
                        <option value="9" {{$employee->tanggungan == '9' ? 'selected' : ''}}>9</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col mb-3">
                    <label for="name" class="form-label">Referal Code</label>
                    <input id="name" class="form-control" readonly="readonly" name="referal_code" type="text" placeholder="John Doe" value="{{$unix}}">
                </div>
            </div>
            <div class="card-header mb-3">
                <h5>Payroll Info</h5>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">BPJS Kesehatan</label>
                    <input type="number" class="form-control" name="bpjs_kes" placeholder="0902xxx" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bpjs_kes : '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">BPJS Ketenagakerjaan</label>
                    <input type="number" class="form-control" name="bpjs_tk" placeholder="0902xxx" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bpjs_tk : '' }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">NPWP</label>
                    <input type="number" class="form-control" name="npwp" placeholder="0902xxx" required value="{{$employee->payrolinfo ? $employee->payrolinfo->npwp : '' }}">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Bank Name</label>
                    <input type="text" class="form-control" name="bank_name" placeholder="BCA" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bank_name : '' }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bank Number</label>
                    <input type="number" class="form-control" name="bank_number" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bank_number : '' }}" placeholder="89120xxx">
                </div>
            </div>
            @if (!$employee->user)
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
                            <input type="checkbox" class="form-check-input" name="permissions[]" value="superadmin_access">
                            <label class="form-check-label">Super Admin</label>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <button class="btn btn-primary w-100 button-biru" type="submit">Update Data</button>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="row mobile">
    <div class="col-lg-12">
        <div class="arrow-back mb-3">
            <a href="#" onclick="goBack()" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Profile</h5>
            </a>
        </div>
        <div class="card custom-card2 mb-6">
            <div class="card-body">
                <h4 class="card-title">Edit My Profile</h4>
                <form method="POST" action="{{ route('employee.update', ['employee' => $employee->nik]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="col mb-3">
                        @if($employee->photo)
                            <img src="{{ asset('images/' . $employee->gambar) }}" alt="Employee Photo" id="photoPreview" class="" style="max-width: 200px; border-radius: 50%;">
                        @else
                            <img src="{{ asset('images/' . $employee->gambar) }}" alt="Default Photo" id="photoPreview" class=" mb-2" style="max-width: 200px;">
                        @endif
                        <input type="file" class="form-control" name="gambar" id="photoInput" accept="image/*">
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Nama lengkap</label>
                            <input id="name" class="form-control" name="nama" type="text" placeholder="John Doe" value="{{$employee->nama}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="Ktp" class="form-label">KTP</label>
                            <input id="ktp" class="form-control" name="ktp" type="number" placeholder="3xxxxxx" value="{{$employee->ktp}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="kode_karyawan" class="form-label">Kode Karyawan</label>
                            <input id="kode_karyawan" class="form-control" name="nik" type="number" placeholder="xxx-xxx-xxx" value="{{$employee->nik}}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{$employee->jabatan}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Agama</label>
                            <input type="text" name="agama" value="{{$employee->agama}}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="js-example-basic-single form-select" data-width="100%" name="jenis_kelamin">
                                <option value="Laki-Laki" {{$employee->jenis_kelamin == 'Laki-Laki' ? 'selected' : ''}}>Laki-Laki</option>
                                <option value="Perempuan" {{$employee->jenis_kelamin == 'Perempuan' ? 'selected' : ''}}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="kode_karyawan" class="form-label">Email</label>
                            <input id="email" class="form-control" name="email" type="email" placeholder="johndoe@champoil.co.id" value="{{$employee->email}}" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="kode_karyawan" class="form-label">Nomor Telepon</label>
                            <input id="telepon" class="form-control" name="telepon" type="number" placeholder="08xxxxxx" value="{{$employee->telepon}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Status Kontrak</label>
                            <select class="js-example-basic-single form-select" data-width="100%" name="status_kontrak">
                                <option value="Contract" {{$employee->status_kontrak == 'Contract' ? 'selected' : ''}}>Kontrak</option>
                                <option value="Permanent" {{$employee->status_kontrak == 'Permanent' ? 'selected' : ''}}>Tetap</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Organisasi</label>
                            <select class="js-example-basic-single form-select" data-width="100%" name="organisasi">
                                <option value="Professional Frontline" {{$employee->organisasi == 'Professional Frontline' ? 'selected' : ''}}>Professional Frontline</option>
                                <option value="Management Leaders" {{$employee->organisasi == 'Management Leaders' ? 'selected' : ''}}>Management Leaders</option>
                                <option value="Frontline Officer" {{$employee->organisasi == 'Frontline Officer' ? 'selected' : ''}}>Frontline Officer</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" name="joindate" value="{{$employee->joindate}}" >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Berakhir</label>
                            <input type="date" class="form-control" name="berakhirkontrak" value="{{$employee->berakhirkontrak}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Tempat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lahir" placeholder="Jakarta" value="{{$employee->tempat_lahir}}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" class="form-control" name="tanggal_lahir" value="{{$employee->tanggal_lahir}}">
                        </div>
                    </div>
                    <div class="mb-3 mb-3">
                        <label class="form-label">alamat</label>
                        <textarea name="alamat" id="" cols="30" rows="10" class=form-control>{{$employee->alamat}}</textarea>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label class="form-label">Status Pernikahan</label>
                            <select class="js-example-basic-single form-select" data-width="100%" name="status_pernikahan">
                                <option value="Married" {{$employee->status_pernikahan == 'Married' ? 'selected' : ''}}>Married</option>
                                <option value="Single" {{$employee->status_pernikahan == 'Single' ? 'selected' : ''}}>Single</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Tanggungan</label>
                            <select class="js-example-basic-single form-select" data-width="100%" name="tanggungan">
                                <option value="0" {{$employee->tanggungan == '0' ? 'selected' : ''}}>0</option>
                                <option value="1" {{$employee->tanggungan == '1' ? 'selected' : ''}}>1</option>
                                <option value="2" {{$employee->tanggungan == '2' ? 'selected' : ''}}>2</option>
                                <option value="3" {{$employee->tanggungan == '3' ? 'selected' : ''}}>3</option>
                                <option value="4" {{$employee->tanggungan == '4' ? 'selected' : ''}}>4</option>
                                <option value="5" {{$employee->tanggungan == '5' ? 'selected' : ''}}>5</option>
                                <option value="6" {{$employee->tanggungan == '6' ? 'selected' : ''}}>6</option>
                                <option value="7" {{$employee->tanggungan == '7' ? 'selected' : ''}}>7</option>
                                <option value="8" {{$employee->tanggungan == '8' ? 'selected' : ''}}>8</option>
                                <option value="9" {{$employee->tanggungan == '9' ? 'selected' : ''}}>9</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="name" class="form-label">Referal Code</label>
                            <input id="name" class="form-control" readonly="readonly" name="referal_code" type="text" placeholder="John Doe" value="{{$unix}}">
                        </div>
                    </div>
                    <div class="card-header mb-3">
                        <h5>Payroll Info</h5>
                    </div>
                    <div class="row mb-3">
                        <div class="col mb-3">
                            <label class="form-label">BPJS Kesehatan</label>
                            <input type="number" class="form-control" name="bpjs_kes" placeholder="0902xxx" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bpjs_kes : '' }}">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">BPJS Ketenagakerjaan</label>
                            <input type="number" class="form-control" name="bpjs_tk" placeholder="0902xxx" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bpjs_tk : '' }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">NPWP</label>
                            <input type="number" class="form-control" name="npwp" placeholder="0902xxx" required value="{{$employee->payrolinfo ? $employee->payrolinfo->npwp : '' }}">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col mb-3">
                            <label class="form-label">Bank Name</label>
                            <input type="text" class="form-control" name="bank_name" placeholder="BCA" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bank_name : '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Bank Number</label>
                            <input type="number" class="form-control" name="bank_number" required value="{{$employee->payrolinfo ? $employee->payrolinfo->bank_number : '' }}" placeholder="89120xxx">
                        </div>
                    </div>
                    @if (!$employee->user)
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
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="dashboard_access" {{ in_array('dashboard_access', $employee->permissions) ? 'checked' : '' }}>
                                    <label class="form-check-label">User</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="hr_access" {{ in_array('hr_access', $employee->permissions) ? 'checked' : '' }}>
                                    <label class="form-check-label">HR</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="superadmin_access" {{ in_array('superadmin_access', $employee->permissions) ? 'checked' : '' }}>
                                    <label class="form-check-label">Super Admin</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="am_access" {{ in_array('am_access', $employee->permissions) ? 'checked' : '' }}>
                                    <label class="form-check-label">Area Manager</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="permissions[]" value="pic_access" {{ in_array('pic_access', $employee->permissions) ? 'checked' : '' }}>
                                    <label class="form-check-label">PIC Project</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <button class="btn btn-primary w-100 button-biru" type="submit">Update Data</button>
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
<style>
    img#photoPreview {
        border-radius: 50%;
        height: 120px;
        width: 120px;
        object-fit: cover;
    }
</style>
<script>
    function goBack() {
        window.history.back();
    }
    document.addEventListener("DOMContentLoaded", function () {
        const photoInput = document.getElementById('photoInput');
        const photoPreview = document.getElementById('photoPreview');

        // Handle file input change to show the preview
        photoInput.addEventListener('change', function () {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };

                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush