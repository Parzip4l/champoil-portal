@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- TopBar -->
<div class="row mb-4 mobile">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('dashboard')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Attendence Request</h5>
            </a>
        </div>
    </div>
</div>

<div class="absen-wrap mb-4">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card custom-card2">
                <div class="card-header">
                    <h5>Request Attendence</h5>
                </div>
                <div class="card-body">
                    <form action="{{route('attendence-request.store')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Type</label>
                                    <select name="status" id="status-select" class="form-select" data-width="100%" required>
                                        <option value="">Select Type</option>
                                        @foreach($typeRequest as $dataRequest)
                                            <option value="{{$dataRequest->code}}">{{$dataRequest->name}}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="employee" value="{{$EmployeeCode}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div id="lembur-input" class="col-md-6" style="display: none;">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Jam Lembur</label>
                                    <input type="number" name="jam_lembur" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Clock In</label>
                                    <input type="time" name="clock_in" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Clock Out</label>
                                    <input type="time" name="clock_out" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Alasan</label>
                                    <input type="text" name="alasan" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">File Pendukung</label>
                                    <span class="text-danger">(Only Pdf & JPG)</span>
                                    <input type="file" name="dokumen" class="form-control">
                                    <input type="hidden" name="aprrove_status" value="Pending">
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary w-100 mt-2 button-biru">Ajukan Permohonan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-6">
            <div class="card custom-card2">
                <div class="card-header">
                    <h5>History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal Diajukan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Type</th>
                                    <th>Status Pengajuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($historyData as $data)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->locale('id_ID')->isoFormat('dddd, D MMMM YYYY') }}</td>
                                        <td>{{$data->tanggal}}</td>
                                        <td>{{$data->status}}</td>
                                        <td>
                                            <span class="badge 
                                                @if($data->aprrove_status === 'Pending') 
                                                    bg-warning 
                                                @elseif($data->aprrove_status === 'Reject') 
                                                    bg-danger 
                                                @elseif($data->aprrove_status === 'Approved') 
                                                    bg-success 
                                                @endif">
                                                {{ $data->aprrove_status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/flatpickr/flatpickr.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
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
  <!-- Absen -->
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btn-absen').on('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude').val(position.coords.latitude);
                    $('#longitude').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-absen').submit();
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<!-- Clockout -->
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btnout').on('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude_out').val(position.coords.latitude);
                    $('#longitude_out').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-absen2').submit();
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<script>
    function formAbsen() {
        document.getElementById("btn-absen").submit();
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status-select');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                const lemburInput = document.getElementById('lembur-input');
                if (this.value === 'L') {
                    lemburInput.style.display = 'block';
                } else {
                    lemburInput.style.display = 'none';
                }
            });
        }
    });
</script>
@endpush