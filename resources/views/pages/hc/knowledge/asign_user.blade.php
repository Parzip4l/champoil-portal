@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Asign Test To Users</h5>
      </div>
      <div class="card-body">
        <form id="form_category" action="{{route('knowledge.save_asign_users')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id_test" value="{{ $id_test }}">
        <div class="row">
            <div class="col-md-6">
                <label for="metode_training">Tipe Test:</label>
                <select class="form-control" id="metode_training" onchange="myFunction()" name="metode_training">
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                </select>
                <div id="offline">
                    <label for="" class="form-label">Lokasi</label>
                    <input type="text" class="form-control" name="lokasi">
                    <label for="" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" name="tanggal">
                    
                </div>
            </div>
            <div class="col-md-6">
                <div id="offline2">
                    <label for="" class="form-label">Jam</label>
                    <input type="time" class="form-control" name="jam">
                    <label for="" class="form-label">Catatan</label>
                    <textarea class="form-control" name="catatan" height="300"></textarea>
                </div>
            </div>
        </div>
        
        
            <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                $nomor = 1;
                            @endphp
                            @foreach ($records as $record)
                            <tr>
                                <td width=20> {{$nomor++}} </td>
                                <td> 
                                <div class="form-check">
                                    <input type="checkbox" name="employee_code[]" value="{{ $record->nik }}" class="form-check-input" id="exampleCheckbox1{{ $record->nik }}">
                                    <label class="form-check-label" for="exampleCheckbox1{{ $record->nik }}">{{ $record->nama }} </label>
                                    
                                </div>
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" id="submit" class="btn btn-primary ml-3" style="float:left">Submit</button>
            
        </form>
      </div>
    </div>
</div>



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
        // Your code here
        $("#offline").hide();
        $("#offline2").hide();
        myFunction = function() {
            var selectedOption = document.getElementById("metode_training").value;
            // Do something with the selected option
            if(selectedOption == "Online"){
                $("#offline").hide();
                $("#offline2").hide();
            }else{
                $("#offline").show();
                $("#offline2").show();
            }
        }
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
                const deleteUrl = "{{ route('knowledge.destroy', ':id') }}".replace(':id', id);
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
    $(document).ready(function() {
        $('#add_quesnioer_list').click(function() {
            var count = $("#list_queessioner #list_soal").length;
            var no = count +1;
            var newRow = '<div id="list_soal"><div class="mb-3">'+
                    '<label class="form-label" for="basic-default-fullname">Quessioner</label>'+
                    '<textarea class="form-control" name="master_soal[]"></textarea>'+
                        '<div class="row">'+
                            '<div class="col-md-10">'+
                                '<div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">'+
                                    '<span id="basic-icon-default-fullname2" class="input-group-text">A</span>'+
                                    '<input type="text" class="form-control" name="jawaban_'+no+'[]" id="basic-icon-default-fullname" placeholder="Option">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2  mt-3">'+
                                '<input type="text" name="point_'+no+'[]" class="form-control" placeholder="Point" id="basic-default-fullname">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-10">'+
                                '<div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">'+
                                    '<span id="basic-icon-default-fullname2" class="input-group-text">B</span>'+
                                    '<input type="text" class="form-control" name="jawaban_'+no+'[]" id="basic-icon-default-fullname" placeholder="Option">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2  mt-3">'+
                                '<input type="text" name="point_'+no+'[]" class="form-control" placeholder="Point" id="basic-default-fullname">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-10">'+
                                '<div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">'+
                                    '<span id="basic-icon-default-fullname2" class="input-group-text">C</span>'+
                                    '<input type="text" class="form-control" name="jawaban_'+no+'[]" id="basic-icon-default-fullname" placeholder="Option">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2  mt-3">'+
                                '<input type="text" name="point_'+no+'[]" class="form-control" placeholder="Point" id="basic-default-fullname">'+
                            '</div>'+
                        '</div>'+
                        '<div class="row">'+
                            '<div class="col-md-10">'+
                                '<div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">'+
                                    '<span id="basic-icon-default-fullname2" class="input-group-text">D</span>'+
                                    '<input type="text" class="form-control" name="jawaban_'+no+'[]" id="basic-icon-default-fullname" placeholder="Option">'+
                                '</div>'+
                            '</div>'+
                            '<div class="col-md-2  mt-3">'+
                                '<input type="text" name="point_'+no+'[]" class="form-control" placeholder="Point" id="basic-default-fullname">'+
                            '</div>'+
                        '</div><hr/>'+
                '</div></div>';
            $('#list_queessioner').append(newRow);
        });
        
    });
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