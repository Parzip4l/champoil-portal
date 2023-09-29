@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="card-header mb-2">
            <div class="row">
                <div class="col-md-6 align-self-center">
                    <h6 class="card-title mb-0">Jadwal Pengiriman</h6>
                </div>
            </div>
        </div>
        <form action="{{route('manual-delivery.UpdateData', $data->id)}}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Tanggal Order</label>
                    <input type="date" class="form-control" name="tanggal_order" value="{{ $data->tanggal_order }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Tanggal Kirim</label>
                    <input type="date" class="form-control" name="tanggal_kirim" value="{{ $data->tanggal_kirim }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Customer</label>
                    <input type="text" class="form-control" name="customer" value="{{ $data->customer }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Nomor SO</label>
                    <input type="text" class="form-control" name="nomor_so" value="{{ $data->nomor_so }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Ekspedisi</label>
                    <input type="text" class="form-control" name="ekspedisi" value="{{ $data->ekspedisi }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Nama Barang</label>
                    <input type="text" class="form-control" name="nama_barang" value="{{ $data->nama_barang }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Total Order</label>
                    <input type="text" class="form-control" name="total_order" value="{{ $data->total_order }}" required>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Sisa Order</label>
                    <input type="text" class="form-control" name="sisa_order" value="{{ $data->sisa_order }}" >
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Driver</label>
                    <select name="driver" class="form-control" id="">
                        <option value="Adi Rusman" {{$data->driver == 'Adi Rusman' ? 'selected' : ''}}>Adi Rusman</option>
                        <option value="Aas" {{$data->driver == 'Aas' ? 'selected' : ''}}>Aas</option>
                        <option value="Febri Andrianto" {{$data->driver == 'Febri Andrianto' ? 'selected' : ''}}>Febri Andrianto</option>
                    </select>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Status</label>
                    <select name="status" class="form-control" id="">
                        <option value="Done" {{$data->status == 'Done' ? 'selected' : ''}}>Done</option>
                        <option value="On Progress" {{$data->status == 'On Progress' ? 'selected' : ''}}>On Progress</option>
                        <option value="On Delivery" {{$data->status == 'On Delivery' ? 'selected' : ''}}>On Delivery</option>
                        <option value="Delayed" {{$data->status == 'Delayed' ? 'selected' : ''}}>Delayed</option>
                        <option value="Cancel" {{$data->status == 'Cancel' ? 'selected' : ''}}>Cancel</option>
                        <option value="On Hold" {{$data->status == 'On Hold' ? 'selected' : ''}}>On Hold</option>
                        <option value="Dikirim Sebagian" {{$data->status == 'Dikirim Sebagian' ? 'selected' : ''}}>Dikirim Sebagian</option>
                    </select>
                </div>
                <div class="col-md-12 mb-2">
                    <label for="" class="form-label">Keterangan</label>
                    <input type="text" class="form-control" name="keterangan" value="{{$data->keterangan}}">
                </div>
                <div class="col-md-12 mt-2">
                    <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
                </div>
            </div>
        </form>
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
            title: 'Hapus Data',
            text: 'Anda Yakin Akan Menghapus Data Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete action here (e.g., send a request to delete the data)
                // Menggunakan ID yang diteruskan sebagai parameter ke dalam URL delete route
                const deleteUrl = "{{ route('contact.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Contact Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Contact Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Contact Failed to Delete',
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
    $(document).ready(function() {
    // Listen for changes in the status dropdown
    $('select.status-dropdown').change(function() {
        const status = $(this).val();
        const dataId = $(this).data('id');
        
        // Get the CSRF token from the meta tag
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        
        // Send an AJAX request to update the status
        $.ajax({
            url: '/update-status',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                id: dataId,
                status: status
            },
            success: function(response) {
                // Handle the response from the server if needed
                console.log('Status updated successfully:', response);
            },
            error: function(error) {
                console.error('Error updating status:', error);
                console.error('XHR status:', status);
                console.error('XHR response:', xhr.responseText);
            }
        });
    });
});
</script>
@endpush