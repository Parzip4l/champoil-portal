@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    .backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #3498db;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
  </style>
@endpush

@section('content')
<div id="loading-backdrop" class="backdrop">
    <div class="spinner"></div>
</div>
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Job Aplicant</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form method="get" class="mb-3">
                        <div class="row">
                            <label for="organization" class="form-label">Filter :</label>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="tanggal" id="daterange_picker">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                        
                        
                    </form>
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Melamar</th>
                                <th>NIK</th>
                                <th>Nama Lengkap</th>
                                <th>Detail</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($records))
                                @php 
                                    $no=1;
                                @endphp
                                @foreach($records as $row)
                                    @php 
                                        if($row->kualifikasi=="1"){
                                            $status="success";
                                            $text="Lolos Kualifikasi";
                                        }else{
                                            $status="danger";
                                            $text="Tidak Lolos Kualifikasi";
                                        }

                                        $height = $row->tb / 100; // Assuming height is given in centimeters, convert it to meters
                                        // Calculate BMI
                                        $bmi = $row->bb / ($height * $height);

                                        
                                    @endphp
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td>
                                            {{ date('d F Y',strtotime($row->tanggal)) }}<br/>
                                            <a href="javascript:void(0)" 
                                               class="btn btn-xs btn-outline-primary"
                                               data-bs-toggle="modal" 
                                               data-bs-target="#titipan-{{$row->id}}">Titipan Client</a>
                                        </td>
                                        <td>{{ $row->nomor_induk }}</td>
                                        <td>{{ $row->nama_lengkap }}</td>
                                        <td>
                                            <ol type="1">
                                                <li>Whatsapp : {{ $row->nomor_wa }}</li>
                                                <li>Usia : {{ $row->usia }} {!! $row->lolos_usia !!}</li>
                                                <li>TB : {{ $row->tb }} {!! $row->lolos_tb !!}</li>
                                                <li>BB : {{ $row->bb }}</li>
                                                <li>BMI : {{ round($bmi,0) }} {!! $row->lolos_bmi !!}</li>
                                            </ol>
                                        </td>
                                        <td><span class="badge bg-{{ $status }}">{{ $text }}</span></td>
                                    </tr>
                                    @php 
                                        $no++;
                                    @endphp
                                    <div class="modal fade" id="titipan-{{$row->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Upload File</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form
                                                        id="dataForm"
                                                        method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="row">
                                                            <div class="col-md-12 mb-2">
                                                                <label for="" class="form-label">Files Upload</label>
                                                                <input type="file" name="bukti_tulis" id="bukti_tulis" class="form-control" required="required">
                                                            </div>
                                                            <div class="col-md-12 mb-2">
                                                                <label for="" class="form-label">Client Name</label>
                                                                <input type="text" name="client_name" id="client_name" class="form-control" required="required">
                                                            </div>
                                                            <div class="col-md-12 mt-2">
                                                                <button class="btn btn-primary w-100" id="submit-form" type="button">Simpan Data</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                            @endif
                            
                        </tbody>
                    </table>
                </div>
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
                const deleteUrl = "{{ route('list-task.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'List Task Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'List Task Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'List Task Failed to Delete',
                        text: 'An error occurred while deleting data.',
                        icon: 'error',
                    });
                });
            }
        });
    }
</script>
<script>
    $('#loading-backdrop').hide();
    $(document).ready(function() {
        $("#submit-form").on('click', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $('#loading-backdrop').show(); // Show loading backdrop (if you have one)

            // Create a FormData object from the form
            let formElement = document.getElementById("dataForm");
            let formData = new FormData(formElement);

            // Send POST request using Axios
            axios.post('https://data.cityservice.co.id/cs/public/api/save-permintaan-client', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                }
            })
            .then(function(response) {
                // Handle success
                console.log(response.data);
                $('#loading-backdrop').hide(); // Hide loading backdrop
                alert('Data has been saved successfully!');
            })
            .catch(function(error) {
                // Handle error
                console.error(error);
                $('#loading-backdrop').hide(); // Hide loading backdrop
                alert('An error occurred. Please try again.');
            });
        });
    });

</script>
<script>
        flatpickr("#daterange_picker", {
            mode: "range",
            dateFormat: "Y-m-d",
            onClose: function(selectedDates, dateStr, instance) {
                console.log(dateStr); // Date range in 'Y-m-d to Y-m-d' format
            }
        });
</script>
@endpush