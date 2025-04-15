@extends('layout.master')
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush
@section('content')
<div class="card custom-card2">
    <div class="card-header d-flex justify-content-between">
        <h5>Daftar Shift</h5>
        <a href="{{ route('company.shifts.create', $companyId) }}" class="btn btn-primary">+ Tambah Shift</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="dataTableExample">
                <thead>
                    <tr>
                        <th>Nama Shift</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        @if($useMultilocation)
                            <th>Lokasi</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($shifts as $shift)
                    <tr>
                        <td>{{ $shift->name }}</td>
                        <td>{{ $shift->start_time }}</td>
                        <td>{{ $shift->end_time }}</td>
                        @if($useMultilocation)
                        <td>{{ $shift->workLocation->name ?? '-' }}</td>
                        @endif
                        <td>
                            <a href="{{ route('company.shifts.edit', [$companyId, $shift->id]) }}" class="btn btn-sm btn-warning">Edit</a>

                            <form action="{{ route('company.shifts.destroy', [$companyId, $shift->id]) }}" method="POST" onsubmit="return confirmDelete(event)" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($shifts->count() > 0)
            @php
                $latestShift = $shifts->sortByDesc('updated_at')->first();
            @endphp
            <div class="mt-3 text-danger">
                Terakhir diubah oleh: 
                <strong>{{ $latestShift->updatedByEmployee->nama ?? 'Tidak diketahui' }}</strong> 
                pada {{ \Carbon\Carbon::parse($latestShift->updated_at)->translatedFormat('d M Y H:i') }}
            </div>
        @endif
    </div>
</div>
@endsection
@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush
@push('custom-scripts')
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#shiftTable').DataTable();
        });

        function confirmDelete(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Shift?',
                text: 'Data akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    e.target.submit();
                }
            });
            return false;
        }
    </script>
@endpush


