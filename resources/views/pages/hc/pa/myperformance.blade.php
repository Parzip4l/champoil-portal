@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<!-- Top Bar -->
<div class="row mb-5 mobile">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('dashboard')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<!-- Profile Card -->
<div class="row mobile">
    <div class="col-md-12">
        <div class="card custom-card2 mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="content-wrap-employee-card d-flex justify-content-between mb-5">
                        <div class="content-left align-self-center">
                            <div class="employee-name mb-1">
                                <h5 class="color-custom-secondary text-uppercase">{{ $employee->nama }}</h5>
                            </div>
                            <div class="employee-title-job">
                                <p class="color-custom-secondary">{{ $employee->jabatan }}</p>
                            </div>
                        </div>
                        <div class="content-right">
                            <div class="gambar">
                                <img src="{{ asset('images/' . $employee->gambar) }}" alt="" class="w-100">
                            </div>
                        </div>
                    </div>
                    <div class="content-wrap-employee-card d-flex justify-content-between">
                        <div class="content-left align-self-center">
                            <div class="employee-title-job">
                                <p class="color-custom">Employee ID</p>
                            </div>
                            <div class="employee-name mb-1">
                                <h5 class="color-custom-secondary text-uppercase">{{ $employee->nik }}</h5>
                            </div>
                        </div>
                        <div class="content-right">
                            <div class="employee-title-job text-right color-custom">
                                <p class="color-custom">Division</p>
                            </div>
                            <div class="employee-name mb-1">
                                <h5 class="text-uppercase color-custom-secondary">{{ $employee->organisasi }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- List Pa Record -->
<div class="row mb-3">
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-body">
                <div class="form-list-wrap-pa">
                    @if($paData->isEmpty())
                        <div class="alert alert-danger text-center">
                            <strong>Tidak ada data Performance Appraisal ditemukan.</strong>
                        </div>
                    @else
                        @foreach($paData as $data)
                        <div class="content-pa-header d-flex justify-content-between">
                            <div class="periode-data mb-4">
                                <p>{{$data->periode}} {{$data->tahun}}</p>
                                
                            </div>
                            <div class="assign-data">
                                @if ($data->approve_byemployee === 'false')
                                    <p class="text-danger">Not Signed</p>
                                @else
                                    <p class="text-success">Already Signed</p>
                                @endif
                            </div>
                            
                        </div>
                        <div class="pa-body mb-3">
                            <div class="nilai">
                                <p>{{$data->nilai_keseluruhan}}</p>
                                @if ($predikatName === 'Baik Sekali')
                                    
                                @elseif ($predikatName === 'Baik')
                                    <h5 class="text-success">{{$predikatName}}</h5>
                                @elseif ($predikatName === 'Cukup')
                                    <h5 class="text-warning">{{$predikatName}}</h5>
                                @elseif ($predikatName === 'Kurang')
                                    <h5 class="text-warning">{{$predikatName}}</h5>
                                @elseif ($predikatName === 'Kurang Sekali')
                                    <h5 class="text-danger">{{$predikatName}}</h5>
                                @endif
                            </div>
                        </div>
                        <p class="text-muted mb-2">Created By: {{$data->created_by}}</p>
                        <a href="{{route('details.Mypa', $data->id)}}" class="btn btn-sm btn-primary w-100">Lihat Details</a>
                        <hr>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- End -->

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/password.js') }}"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const authCheck = document.getElementById("authCheck");
        const passwordInput = document.getElementById("passwordInput");
        const CurrentPass = document.getElementById("current_password");
        const passwordConfirmationInput = document.getElementById("passwordConfirmationInput");

        authCheck.addEventListener("change", function() {
            if (authCheck.checked) {
                passwordInput.type = "text";
                passwordConfirmationInput.type = "text";
                CurrentPass.type = "text";
            } else {
                passwordInput.type = "password";
                passwordConfirmationInput.type = "password";
                CurrentPass.type = "password";
            }
        });
    });
</script>
<!-- Clear Cache -->
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '{{ session('success') }}',
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
        });
    @endif

    document.getElementById('clear-cache-link').addEventListener('click', function(event) {
        event.preventDefault();
        fetch('/clear-cache')
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    icon: 'success', // Ganti menjadi 'success' atau 'error' berdasarkan hasil permintaan
                    title: 'Clear Cache',
                    text: data, // Menampilkan pesan hasil dalam SweetAlert
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>

@endpush