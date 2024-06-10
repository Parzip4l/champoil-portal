@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Result Medis</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTableExample" class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Melamar</th>
                                <th>Data Pelamar</th>
                                <th>Posisi Dilamar</th>
                                <th>IQ TEST</th>
                                <th>EQ TEST</th>
                                <th>Value Test</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($records['records'])
                                @php 
                                    $no=1;
                                @endphp
                                @foreach($records['records'] as $row)
                                @php
                                $score_iq = explode(' / ',$row['score_iq']);
                                $score_eq = explode(' / ',$row['score_eq']);
                                if($score_eq[0] >= 70){
                                    $label_eq = 'check-circle';
                                    $class_eq='success';
                                }else if($score_eq[0] > 0 && $score_eq[0] < 70){
                                    $label_eq = 'slash';
                                    $class_eq='danger';
                                }else if(empty($score_eq[0]) || $score_eq[0]==NULL || $score_eq[0]==''){
                                    $label_eq = 'refresh-cw';
                                    $class_eq='warning';
                                }
                                
                                if($score_iq[0] >= 700){
                                    $label_iq = 'check-circle';
                                    $class_iq ='success';
                                }else if($score_iq[0] > 0 && $score_iq[0] < 700){
                                    $label_iq = 'slash';
                                    $class_iq ='danger';
                                }else if(empty($score_iq[0]) || $score_iq[0]==NULL || $score_iq[0]==''){
                                    $label_iq = 'refresh-cw';
                                    $class_iq='warning';
                                }
                                
                                if( $row['score_tech'] >= 0 && $row['score_tech'] <= 4 && $row['score_tech'] !=NULL && $row['score_tech'] !=''){
                                    $label_tech = 'check-circle';
                                    $class_tech ='success';
                                }else if($row['score_tech'] > 4){
                                    $label_tech = 'slash';
                                    $class_tech ='danger';
                                }else if($row['score_tech']===NULL || $row['score_tech']===''){
                                    $label_tech = 'refresh-cw';
                                    $class_tech='warning';
                                }
                                @endphp
                                    <tr>
                                        <td>{{ $no }}</td>
                                        <td> {{ date('d F Y',strtotime($row['tanggal'])) }}</td>
                                        <td>
                                            Nama : {{ $row['nama_lengkap'] }}<br/>
                                            NIK : {{ $row['nomor_induk'] }}<br/>
                                            Nomor WA : {{ $row['nomor_wa'] }}<br/>
                                        </td>
                                        <td>{{ $row['jabatan'] }}</td>
                                        <td>
                                            <button type="button" class="btn btn-outline-{{$class_iq}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $row['score_iq']?$row['score_iq']:'Belum Mengisi'}}">
                                                <i data-feather="{{$label_iq }}"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-{{$class_eq}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $row['score_eq']?$row['score_eq']:'Belum Mengisi' }}">
                                                <i data-feather="{{$label_eq }}"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-outline-{{$class_tech}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $row['score_tech']?$row['score_tech']:'Belum Mengisi' }}">
                                                <i data-feather="{{$label_tech }}"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @php 
                                        $no++;
                                    @endphp
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