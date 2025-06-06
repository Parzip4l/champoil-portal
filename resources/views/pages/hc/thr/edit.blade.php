@extends('layout.master')

@push('plugin-styles')
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row mb-4">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('thr')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">THR Data</h5>
            </div>
            <div class="card-body">
                <form action="{{route('thr.update', $data->id)}}" method="POST">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label for="" class="form-label">Karyawan</label>
                            <input type="text" class="form-control" value="{{ \App\Employee::where('nik', $data->employee_code)->value('nama') ?? 'Nama tidak ditemukan' }}">
                            <input type="hidden" class="form-control" name="employee_code" value="{{$data->employee_code}}">
                        </div>
                        <div class="title mt-2 mb-2">
                            <h5>Allowence</h5>
                            <hr>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">Gaji Pokok</label>
                            <input type="number" name="gaji_pokok" class="form-control allowance" value="{{$data->gaji_pokok}}" required>   
                        </div>
                        @php
                            $dataArray = json_decode($data->allowances, true);
                            $datadeduction = json_decode($data->deductions, true);
                        @endphp
                        @foreach($dataArray['data'] as $id => $value)
                            <div class="col-md-6 mb-2">
                                <label for="" class="form-label">{{ \App\THR\ThrComponentModel::where('id', $id)->value('front_text') ?? 'Nama tidak ditemukan' }}</label>
                                <input type="number" name="allowance[{{$id}}][]" class="form-control allowance" value="{{$value[0]}}" required>   
                            </div>
                        @endforeach
                        <div class="title mt-2 mb-2">
                            <h5>Deduction</h5>
                            <hr>
                        </div>
                        @foreach($datadeduction['data'] as $id => $value)
                        <div class="col-md-6 mb-2">
                            <label for="" class="form-label">{{ \App\THR\ThrComponentModel::where('id', $id)->value('front_text') ?? 'Nama tidak ditemukan' }}</label>
                            <input type="number" name="deduction[{{$id}}][]" class="form-control deduction" value="{{$value[0]}}" id="t_deduction" required>   
                        </div>
                        @endforeach
                    </div>
                    <div class="col-md-12 mt-2">
                        <button class="btn btn-primary w-100" type="submit">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End -->
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
  <script src="{{ asset('assets/js/select2.js') }}"></script>
  <script>
    function showDeleteDataDialog(id) {
        Swal.fire({
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true, 
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('thr-component.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Component Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Component Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Component Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
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
<script>
function calculateTotalAllowance() {
    let totalAllowance = 0;
    // Loop through all allowance inputs and sum their values
    document.querySelectorAll('.allowance').forEach(function(input) {
        totalAllowance += parseFloat(input.value || 0);
    });
    // Update the total allowance input
    document.getElementById('t_allowance').value = totalAllowance;
}
</script>
@endpush