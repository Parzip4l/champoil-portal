@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="col-xl">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Form Quessioner</h5>
      </div>
      <div class="card-body">
        <form id="form_category" action="{{route('knowledge.save_soal')}}" method="POST" enctype="multipart/form-data">
        @csrf
            <input type="hidden" value="{{ $value_master->id }}" name="master_test">
            <div id="list_queessioner">
                
                    <?php if(!empty($result)){ ?>
                    @foreach($result as $row)
                    <div id="list_soal">
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">Quessioner</label>
                        <textarea class="form-control" name="master_soal[]">{{ $row->master_soal }}</textarea>
                        <?php 
                            $no="A"; 
                            $number=1;
                        ?>
                            @foreach($row->jawaban as $jawaban)
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">
                                        <span id="basic-icon-default-fullname2" 
                                              class="input-group-text">{{ $no }}</span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="jawaban_<?php echo $number ?>[]"
                                               value="{{ $jawaban->jawaban }}"
                                               id="basic-icon-default-fullname" 
                                               placeholder="Option">
                                    </div>
                                </div>
                                <div class="col-md-2  mt-3">
                                    <input type="text" 
                                           name="point_<?php echo $number ?>[]" 
                                           class="form-control" 
                                           value="{{ $jawaban->point }}"
                                           placeholder="Point"
                                           id="basic-default-fullname">
                                </div>
                            </div>
                            <?php 
                                $no++; 
                                $number++;    
                            ?>
                            @endforeach
                        
                    </div>
                    </div>
                    @endforeach
                    <?php }else{ ?>
                    <div id="list_soal">
                    <div class="mb-3">
                        <label class="form-label" for="basic-default-fullname">Quessioner</label>
                        <textarea class="form-control" name="master_soal[]"></textarea>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">
                                        <span id="basic-icon-default-fullname2" 
                                              class="input-group-text">A</span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="jawaban_1[]"
                                               id="basic-icon-default-fullname" 
                                               placeholder="Option">
                                    </div>
                                </div>
                                <div class="col-md-2  mt-3">
                                    <input type="text" 
                                           name="point_1[]" 
                                           class="form-control" 
                                           placeholder="Point"
                                           id="basic-default-fullname">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">
                                        <span id="basic-icon-default-fullname2" 
                                              class="input-group-text">B</span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="jawaban_1[]"
                                               id="basic-icon-default-fullname" 
                                               placeholder="Option">
                                    </div>
                                </div>
                                <div class="col-md-2  mt-3">
                                    <input type="text" 
                                           name="point_1[]" 
                                           class="form-control" 
                                           placeholder="Point"
                                           id="basic-default-fullname">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">
                                        <span id="basic-icon-default-fullname2" 
                                              class="input-group-text">C</span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="jawaban_1[]"
                                               id="basic-icon-default-fullname" 
                                               placeholder="Option">
                                    </div>
                                </div>
                                <div class="col-md-2  mt-3">
                                    <input type="text" 
                                           name="point_1[]" 
                                           class="form-control" 
                                           placeholder="Point"
                                           id="basic-default-fullname">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="input-group input-group-merge mt-3" style="margin-left:30px; padding-right:10px">
                                        <span id="basic-icon-default-fullname2" 
                                              class="input-group-text">D</span>
                                        <input type="text" 
                                               class="form-control" 
                                               name="jawaban_1[]"
                                               id="basic-icon-default-fullname" 
                                               placeholder="Option">
                                    </div>
                                </div>
                                <div class="col-md-2  mt-3">
                                    <input type="text" 
                                           name="point_1[]" 
                                           class="form-control" 
                                           placeholder="Point"
                                           id="basic-default-fullname">
                                </div>
                            </div><hr/>
                        
                    </div>
                    </div>
                    <?php } ?>
                
                
            </div>
            
            <a href="javascript:void(0)" id="add_quesnioer_list" class="btn btn-outline-warning">Add Quessioner</a>
            <button type="submit" id="submit" class="btn btn-primary">Submit</button>
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