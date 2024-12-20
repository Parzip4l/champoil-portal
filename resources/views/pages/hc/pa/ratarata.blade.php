@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif  

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="row mb-4">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{route('setting.pa')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Kembali</h5>
            </a>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Performance Appraisal</h6>
                </div>
                <div class="tombol-pembantu d-flex">
                    <a href="{{route('pa.download')}}" class="btn btn-primary">Download Report</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTableExample" class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Periode</th>
                            <th>Tahun</th>
                            <th>Nilai Rata - Rata</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                        $no = 1;
                        @endphp
                        @foreach ($performanceData as $data)
                            @php
                                $backgroundClass = '';
                                if ($data['predikat_name'] === 'Baik') {
                                    $backgroundClass = 'bg-baik';
                                } elseif ($data['predikat_name'] === 'Baik Sekali') {
                                    $backgroundClass = 'bg-baik-sekali';
                                } elseif ($data['predikat_name'] === 'Cukup') {
                                    $backgroundClass = 'bg-cukup';
                                } elseif ($data['predikat_name'] === 'Kurang') {
                                    $backgroundClass = 'bg-kurang';
                                } elseif ($data['predikat_name'] === 'Kurang Sekali') {
                                    $backgroundClass = 'bg-kurang-sekali';
                                }
                                
                            @endphp
                            <tr class="{{ $backgroundClass }}">
                                <td>{{$no++}}</td>
                                <td>{{ $data['employee_name'] }}</td>
                                <td>{{ $data['level'] }}</td>
                                <td>{{ $data['periode'] ?? 'Tidak Tersedia' }}</td>
                                <td>{{ $data['tahun'] ?? 'Tidak Tersedia' }}</td>
                                <td>{{ number_format($data['average_nilai'], 2) }}</td>
                                <td>{{ $data['predikat_name'] ?? 'Tidak Ada Predikat' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>
<!-- End Modal Kategori -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script>
  $(function() {
    'use strict';

    // Inisialisasi DataTable
    $('#dataTableExample').DataTable({
      "aLengthMenu": [
        [10, 30, 50, -1],
        [10, 30, 50, "All"]
      ],
      "iDisplayLength": 10,
      "ordering": false, // Nonaktifkan ordering
      "language": {
        search: ""
      }
    });

    // Tambahkan placeholder untuk input pencarian
    $('#dataTableExample').each(function() {
      var datatable = $(this);
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
</script>
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
                const deleteUrl = "{{ route('pa.destroy', ':id') }}".replace(':id', id);
                fetch(deleteUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then((response) => {
                    // Handle the response as needed (e.g., show alert if data is deleted successfully)
                    if (response.ok) {
                        Swal.fire({
                            title: 'Data Successfully Deleted',
                            icon: 'success',
                        }).then(() => {
                            window.location.reload(); // Refresh halaman setelah menutup alert
                        });
                    } else {
                        // Handle error response if needed
                        Swal.fire({
                            title: 'Data Failed to Delete',
                            text: 'An error occurred while deleting data.',
                            icon: 'error',
                        });
                    }
                }).catch((error) => {
                    // Handle fetch error if needed
                    Swal.fire({
                        title: 'Data Failed to Delete',
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
<style>
    .bg-baik {
        background-color: #d4edda!important; /* Hijau muda */
    }

    .bg-baik-sekali {
        background-color: #c3e6cb!important; /* Hijau lebih terang */
    }

    .bg-cukup {
        background-color: #fff3cd!important; /* Kuning muda */
    }

    .bg-kurang {
        background-color: #f8d7da!important; /* Merah muda */
    }

    .bg-kurang-sekali {
        background-color: #f5c6cb!important; /* Merah lebih gelap */
    }
</style>
@endpush