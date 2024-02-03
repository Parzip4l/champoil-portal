@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <h5 class="mb-0 align-self-center">Project Details </h5>
            </div>
            <div class="card-body">
                <div class="form-wrap">
                    <form action="{{route('project-details.update', $projectDetails->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="kebutuhan detail-wrap" id="KebutuhanDetails">
                                <div class="content-wrap">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Jabatan Anggota</label>
                                                <select class="form-control" id="jabatan_select" name="jabatan">
                                                    @foreach ($jabatan as $data)
                                                        <option value="{{$data->name}}">{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Gaji Pokok</label>
                                                <input type="number" name="p_gajipokok" class="form-control allowences GajiPokok" id="GajiPokok" value="{{$projectDetails->p_gajipokok}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran BPJS TK</label>
                                                <input type="number" name="p_bpjstk" class="form-control allowences p_BPJS_TK" id="p_BPJS_TK" value="{{$projectDetails->p_bpjstk}}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran BPJS KS</label>
                                                <input type="number" name="p_bpjs_ks" class="form-control allowences bpjsks" id="bpjsks" required value="{{$projectDetails->p_bpjs_ks}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran THR</label>
                                                <input type="number" name="p_thr" class="form-control allowences thr" id="thr" required value="{{$projectDetails->p_thr}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Tunjangan Kerja</label>
                                                <input type="number" name="p_tkerja" class="form-control allowences kerja" id="kerja" required value="{{$projectDetails->p_tkerja}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Tunjangan Seragam</label>
                                                <input type="number" name="p_tseragam" class="form-control allowences seragam" id="seragam" required value="{{$projectDetails->p_tseragam}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Tunjangan Lain Lain</label>
                                                <input type="number" name="p_tlain" class="form-control allowences lainlain" id="lainlain" required value="{{$projectDetails->p_tlain}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Training</label>
                                                <input type="number" name="p_training" class="form-control allowences training" id="training" required value="{{$projectDetails->p_training}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Operasional</label>
                                                <input type="number" name="p_operasional" class="form-control allowences operasional" id="operasional" required value="{{$projectDetails->p_operasional}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Membership Plan</label>
                                                <input type="number" name="p_membership" class="form-control MemberShipAwal" id="MemberShipAwal" required value="{{$projectDetails->p_membership}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Real Deduction</label>
                                                <input type="number" name="r_deduction" class="form-control real_deduction" id="real_deduction" required value="{{$projectDetails->r_deduction}}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Project Deduction</label>
                                                <input type="number" name="p_deduction" class="form-control project-deduction" id="project-deduction" required value="{{$projectDetails->rate_harian}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Gaji Sebulan</label>
                                                <input type="number" name="tp_bulanan" class="form-control rate_bulan" id="rate_bulan" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-button-wrap mb-2">
                                <button type="submit" class="btn btn-md btn-success w-100">Update Data</button>
                            </div>
                        </div>
                    </form>
                </div>
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
    <script src="{{ asset('assets/js/project.js') }}"></script>
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