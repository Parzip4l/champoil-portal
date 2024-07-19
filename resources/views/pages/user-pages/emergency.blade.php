@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- Top Bar -->
<div class="row mb-4 mobile">
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
<div class="row">
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
    <div class="col-md-12">
        <div class="card custom-card2 mb-3">
            <div class="card-header">
                <h5>Emergency Request</h5>
            </div>
            <div class="card-body">
                <form action="{{route('emergency.store')}}" method="POST" id="form-emergency">
                    @csrf 
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Kategori</label>
                        <select name="kategori" id="" class="form-control">
                            <option value="">Pilih Kategori</option>
                            @foreach($category as $data)
                            <option value="{{$data->name}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="user" value="{{ $employee->nama }}">
                    <input type="hidden" name="status" value="Pending">
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <div class="form-group mb-2">
                        <label for="" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" id=""></textarea>
                    </div>
                    <button class="btn btn-danger w-100" id="btnsubmit" type="submit">Buat Laporan</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12 mb-6">
        <div class="card custom-card2 mb-3">
            <div class="card-header">
                <h5>History Emergency</h5>
            </div>
            <div class="card-body">
                <div class="history-wrap">
                    @foreach($emergency as $history)
                    <div class="item-history d-flex justify-content-between mb-2">
                        <div class="left-item">
                            <div class="category">
                                <h6>{{$history->category}}</h6>
                            </div>
                            <div class="tanggal-pengajuan">
                                <p class="text-muted">{{$history->created_at}}</p>
                            </div>
                        </div>
                        <div class="right-item">
                            @if($history->status == 'Done')
                                <span class="badge rounded-pill bg-success">{{ $history->status }}</span>
                            @elseif($history->status == 'Pending')
                                <span class="badge rounded-pill bg-warning">{{ $history->status }}</span>
                            @elseif($history->status == 'On Progress')
                                <span class="badge rounded-pill bg-primary">{{ $history->status }}</span>
                            @elseif($history->status == 'Canceled')
                                <span class="badge rounded-pill bg-danger">{{ $history->status }}</span>
                            @endif
                        </div>
                    </div>
                    <form action="{{route('cancel.status', $history->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Canceled">
                        <button class="btn btn-sm btn-danger w-100 @if($history->status == 'Canceled') d-none @endif" type="submit">
                            Cancel Request
                        </button>
                    </form>
                    @endforeach
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
</script>
<script>
    $(document).ready(function () {
        // Mengambil data lokasi pengguna saat tombol absen ditekan
        $('#btnsubmit').on('click', function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    // Mengisi nilai hidden input dengan data lokasi pengguna
                    $('#latitude').val(position.coords.latitude);
                    $('#longitude').val(position.coords.longitude);

                    // Mengirim form absen
                    $('#form-emergency').submit();
                }, function(error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        // Pengguna menolak izin lokasi
                        alert('Anda perlu memberikan izin lokasi untuk menggunakan fitur ini');
                    }
                });
            } else {
                alert('Geolocation tidak didukung oleh browser Anda');
            }
        });
    });
</script>
<script>
document.querySelectorAll('.status-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var id = this.getAttribute('data-id');
        var status = this.getAttribute('data-status');

        fetch(`/emergency/update-status/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`status-${id}`).innerText = status;
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Status updated successfully',
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update status',
                });
            }
        }).catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred',
            });
        });
    });
});
</script>
@endpush