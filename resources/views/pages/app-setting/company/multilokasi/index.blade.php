@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <h5>Daftar Lokasi Kerja</h5>
            </div>
            <div class="card-body">
                @if($useMultilocation)
                    <a href="{{ route('company.work-locations.create', $companyId) }}" class="btn btn-primary mb-3">+ Tambah Lokasi</a>
                @else
                    <div class="alert alert-warning">
                        <strong>Multi-lokasi kerja belum diaktifkan!</strong>
                        Aktifkan pengaturan ini di <a href="{{ route('company-settings.edit', $companyId) }}">pengaturan perusahaan</a>.
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table" id="dataTableExample">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Koordinat</th>
                                <th>Radius</th>
                                <th>Gaji Bulanan</th>
                                <th>Rate Harian</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($locations as $loc)
                                <tr>
                                    <td>{{ $loc->name }}</td>
                                    <td>{{ $loc->resolved_address ?? '-' }}</td>
                                    <td>{{ $loc->latitude }}, {{ $loc->longitude }}</td>
                                    <td>{{ $loc->radius }} KM</td>
                                    <td>Rp{{ number_format($loc->monthly_salary ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($loc->daily_rate ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('company.work-locations.edit', [$companyId, $loc->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <button class="btn btn-sm btn-danger" onclick="showDeleteDataDialog({{ $loc->id }})">Hapus</button>
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
@endsection
@push('plugin-scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
        function showDeleteDataDialog(id) {
            Swal.fire({
                title: 'Hapus Lokasi?',
                text: 'Data ini akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const deleteUrl = "{{ route('company.work-locations.destroy', [$companyId, ':id']) }}".replace(':id', id);

                    fetch(deleteUrl, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    }).then((response) => {
                        if (response.ok) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Lokasi telah dihapus.',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', 'Gagal menghapus lokasi.', 'error');
                        }
                    }).catch((error) => {
                        Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
                    });
                }
            });
        }
    </script>
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
@endpush