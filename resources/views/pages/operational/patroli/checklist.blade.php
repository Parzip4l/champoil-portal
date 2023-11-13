@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<form action="{{ route('save_patroli') }}" method="POST" enctype="multipart/form-data">
@csrf
<input type="hidden" id="unix_code" name="unix_code" value="{{ $master->unix_code }}">
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">{{ $master->judul }}</h5>
            </div>
            <div class="card-body">
                
                    @if($master->list_task)
                        @php 
                            $no=0;
                        @endphp 

                        @foreach($master->list_task as $record)
                            <label>{{ $record->task }}</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="1-{{ $record->id }}" name="status{{ $no }}" id="flexRadioDefault1{{ $record->id }}">
                                <label class="form-check-label" for="flexRadioDefault1{{ $record->id }}">
                                    <i class="me-2 icon-lg" data-feather="check"></i>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" value="0-{{ $record->id }}" name="status{{ $no }}" id="flexRadioDefault2{{ $record->id }}">
                                <label class="form-check-label" for="flexRadioDefault2{{ $record->id }}">
                                <i class="me-2 icon-lg" data-feather="x"></i>
                                </label>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="keterangan[]" class="form-control" id="keterangan" placeholder="Masukan Keterangan jika kondisi tidak baik">
                            </div>
                            @php 
                                $no++;
                            @endphp 
                        @endforeach

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Upload Foto</label>
                            <input type="file" name="foto" class="form-control" id="upload">
                        </div>

                    @endif

                
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">TEMUAN</h5>
            </div>
            <div class="card-body">
                
                <div id="list_temuan">
                    
                </div>
                <a href="javascript:void(0)" class="btn btn-xs btn-secondary" id="add_temuan">Tambah Temuan</a>
                
                
            </div>
        </div>
    </div>
</div>
<div class="d-grid gap-2">
  <button class="btn btn-primary btn-xs" type="submit">Send Patrol</button>
</div>
</form>
<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script>
    $(document).ready(function() {
        $('#add_temuan').click(function() {
            var count = $("#list_temuan #temuan").length;
            var no = count +1;
            var newRow = '<div id="temuan">'+
                            '<div class="mb-3">'+
                                '<label class="form-label" for="basic-default-fullname">Temuan '+no+'</label>'+
                                '<div class="row">'+
                                    '<div class="col-md-2  mt-3">'+
                                        '<textarea id="temuan_'+no+'" class="form-control" name="temuan[]"></textarea>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                            '<div class="mb-3">'+
                                '<label class="form-label" for="basic-default-fullname">Tindakan</label>'+
                                '<div class="row">'+
                                    '<div class="col-md-2  mt-3">'+
                                        '<textarea id="tindaka_'+no+'" class="form-control" name="tindakan[]"></textarea>'+
                                    '</div>'+
                                '</div>'+
                            '</div><hr/>'+
                        '</div>';
            $('#list_temuan').append(newRow);
        });

    });
  
    
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
                const deleteUrl = "{{ route('task.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Task Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Task Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Task Failed to Delete',
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
@endpush