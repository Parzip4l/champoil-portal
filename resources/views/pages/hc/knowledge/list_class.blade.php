@extends('layout.master')
<style>

</style>
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush
@php 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
    $feedback = \App\Feedback::where('name', Auth::user()->name)->first();
    $dataLogin = json_decode(Auth::user()->permission);
@endphp
@section('content')
<div class="card custom-card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="content-wrap-employee-card d-flex justify-content-between mb-5">
                <div class="content-left align-self-center">
                    <div class="employee-name mb-1">
                        <h5 class="text-white text-uppercase">{{ $employee->nama }}</h5>
                    </div>
                    <div class="employee-title-job">
                        <p>{{ $employee->jabatan }}</p>
                    </div>
                </div>
                <div class="content-right">
                    <div class="gambar">
                        <img src="{{ asset('images/' . $employee->gambar) }}" alt="" class="w-100">
                    </div>
                </div>
            </div>
            <div class="content-wrap-employee-card d-flex justify-content-between">
                <div class="content-left align-self-center">
                    <div class="employee-title-job">
                        <p>Employee ID</p>
                    </div>
                    <div class="employee-name mb-1">
                        <h5 class="text-white text-uppercase">{{ $employee->nik }}</h5>
                    </div>
                </div>
                <div class="content-right">
                    <div class="employee-title-job text-right">
                        <p>Division</p>
                    </div>
                    <div class="employee-name mb-1">
                        <h5 class="text-white text-uppercase">{{ $employee->organisasi }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="lms-wrap mt-2">
    <div class="card custom-card2">
        <div class="card-header">
            <h5>Tes yang sudah dikerjakan</h5>  
        </div>
        <div class="card-body">
            <div class="finished-learning-wrap">
            @foreach($test_finis as $row)
                <div class="card custom-card2 mb-2">
                    <div class="card-body">
                        <div class="title-learning d-flex justify-content-between mb-2">
                            <h6>{{ $row->title }}</h6>
                            <p class="text-muted">{{ \Carbon\Carbon::parse($row->updated_at)->format('d F Y') }}</p>
                        </div>
                        <div class="method-type mb-4">
                            <span class="badge bg-success">Online Tes</span>
                        </div>
                        <div class="my-score">
                            <h6>Nilai Saya</h6>
                            <h3>{{ $row->total_point}}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>
</div>

<div class="lms-wrap mt-2">
    <div class="card custom-card2">
        <div class="card-header">
            <h5>Tes Yang Belum Dikerjakan</h5>
        </div>
        <div class="card-body">
            <div class="wrap-test-list">
                @foreach($asign_test as $row_asign_test)
                    @if($row_asign_test->metode_training == "Online")
                    <div class="row mb-3">
                        <div class="list-test-item">
                            <div class="card custom-card2">
                                <div class="card-body">
                                    <div class="details-test">
                                        <div class="title d-flex mb-2">
                                            <i class="me-2 icon-sm align-self-center" data-feather="edit-2"></i>
                                            <p class="text-muted align-self-center">{{$row_asign_test->title}}</p>
                                        </div>
                                        <div class="time d-flex mb-2">
                                            <i class="me-2 icon-sm align-self-center" data-feather="clock"></i>
                                            <p class="text-muted align-self-center">{{$row_asign_test->durasi}} Jam</p>
                                        </div>
                                        <div class="type d-flex mb-2">
                                            <i class="me-2 icon-sm align-self-center" data-feather="file"></i>
                                            <p class="text-muted align-self-center">{{$row_asign_test->metode_training}}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('read_test', ['id' => $row_asign_test->id_test]) }}" class="btn btn-sm btn-primary w-100">Mulai Tes</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    @if($row_asign_test->start_class == 1)
                        <a href="{{ route('read_test', ['id' => $row_asign_test->id_test]) }}" class="btn btn-sm btn-primary">Mulai Tes</a>
                    @else
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Class Offline
                    </button>
                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Informasi Class</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                <p>Materi : PTSD</p>
                                    @php
                                        $data = json_decode($row_asign_test->notes_training);
                                        foreach ($data as $key => $value) {
                                            if($key=="tanggal"){
                                                echo "<p>".ucfirst($key) . ': ' . date('d F Y',strtotime($value)) . '<p>';
                                            }else{
                                                echo "<p>".ucfirst($key) . ': ' . $value . '<p>';
                                            }
                                            
                                        }
                                    @endphp
                                </div>
                            </div>
                            </div>
                        </div>
                        @endif
                    @endif
                @endforeach
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