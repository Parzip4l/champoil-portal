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
                <h5 class="mb-0 align-self-center">Buat Project Baru</h5>
            </div>
            <div class="card-body">
                <div class="form-wrap">
                    <form action="{{route('project.store')}}" method="POST">
                        @csrf   
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Nama Project</label>
                                    <input type="text" name="name" class="form-control" placeholder="Nama Project" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Nama Badan</label>
                                    <input type="text" name="badan" class="form-control" placeholder="Nama Badan" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Latitude</label>
                                    <input type="text" name="latitude" class="form-control" placeholder="Eg, -6.12665219" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Longitude</label>
                                    <input type="text" name="longtitude" class="form-control" placeholder="Eg, 106.7594188" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">Contact Start</label>
                                    <input type="date" name="contract_start" class="form-control" placeholder="Eg, -6.12665219" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="" class="form-label">End Contract</label>
                                    <input type="date" name="end_contract" class="form-control" placeholder="Eg, 106.7594188" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="card-header mb-2">
                                <h4>Project Details</h4>
                            </div>
                            <div class="kebutuhan detail-wrap" id="KebutuhanDetails">
                                <div class="content-wrap">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label">Jabatan Anggota</label>
                                                <select class="form-control" id="jabatan_select" name="jabatan[]">
                                                    @foreach ($jabatan as $data)
                                                        <option value="{{$data->name}}">{{$data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Kebutuhan Anggota</label>
                                                <input type="number" name="kebutuhan[]" class="form-control kebutuhan-anggota" id="KebutuhanAnggota" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Gaji Pokok</label>
                                                <input type="number" name="p_gajipokok[]" class="form-control allowences GajiPokok" id="GajiPokok" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran BPJS TK</label>
                                                <input type="number" name="p_bpjstk[]" class="form-control allowences p_BPJS_TK" id="p_BPJS_TK" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran BPJS KS</label>
                                                <input type="number" name="p_bpjs_ks[]" class="form-control allowences bpjsks" id="bpjsks" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran THR</label>
                                                <input type="number" name="p_thr[]" class="form-control allowences thr" id="thr" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Tunjangan Kerja</label>
                                                <input type="number" name="p_tkerja[]" class="form-control allowences kerja" id="kerja" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Tunjangan Seragam</label>
                                                <input type="number" name="p_tseragam[]" class="form-control allowences seragam" id="seragam" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Tunjangan Lain Lain</label>
                                                <input type="number" name="p_tlain[]" class="form-control allowences lainlain" id="lainlain" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Training</label>
                                                <input type="number" name="p_training[]" class="form-control allowences training" id="training" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Operasional</label>
                                                <input type="number" name="p_operasional[]" class="form-control allowences operasional" id="operasional" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Membership Plan</label>
                                                <input type="number" name="p_membership[]" class="form-control MemberShipAwal" id="MemberShipAwal" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Real Deduction</label>
                                                <input type="number" name="r_deduction[]" class="form-control real_deduction" id="real_deduction" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Project Deduction</label>
                                                <input type="number" name="p_deduction[]" class="form-control project-deduction" id="project-deduction" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-header mb-2">
                                        <h4>Total Details</h4>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Penawaran Gapok</label>
                                                <input type="number" name="tp_gapok[]" class="form-control TotalGapok" id="TotalGapok" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total BPJS TK</label>
                                                <input type="number" name="tp_bpjstk[]" class="form-control TotalBPJSTK" id="TotalBPJSTK" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total BPJS KS</label>
                                                <input type="number" name="tp_bpjsks[]" class="form-control TotalBPJSKS" id="TotalBPJSKS" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total THR</label>
                                                <input type="number" name="tp_thr[]" class="form-control totalthr" id="totalthr" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Tunjangan Kerja</label>
                                                <input type="number" name="tp_tunjangankerja[]" class="form-control totalkerja" id="totalkerja" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Tunjangan Seragam</label>
                                                <input type="number" name="tp_tunjanganseragam[]" class="form-control TotalTSeragam" id="TotalTSeragam" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Tunjangan Lainnya</label>
                                                <input type="number" name="tp_tunjanganlainnya[]" class="form-control TotalTLainnya" id="TotalTLainnya" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Training</label>
                                                <input type="number" name="tp_training[]" class="form-control TotalTraining" id="TotalTraining" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Operasional</label>
                                                <input type="number" name="tp_operasional[]" class="form-control TotalOperasional" id="TotalOperasional" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total PPN</label>
                                                <input type="number" name="tp_ppn[]" class="form-control TotalPPN" id="TotalPPN" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total PPH</label>
                                                <input type="number" name="tp_pph[]" class="form-control TotalPPH" id="TotalPPH" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Total Cashin</label>
                                                <input type="number" name="tp_cashin[]" class="form-control TotalCashin" id="TotalCashin" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Keterangan</label>
                                                <input type="text" name="keterangan[]" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Total</label>
                                                <input type="number" name="tp_total[]" class="form-control penawaran_total" id="penawaran_total" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Penawaran Total Membership Plan</label>
                                                <input type="number" name="tp_membership[]" class="form-control TotalMembership" id="TotalMembership" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Bulanan</label>
                                                <input type="number" name="tp_bulanan[]" class="form-control rate_bulan" id="rate_bulan" required readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label for="" class="form-label">Rate Harian</label>
                                                <input type="number" name="rate_harian[]" class="form-control rate_harian" id="rate_harian" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeProductRow(this)">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="add-button-wrap mb-2">
                                <button type="button" id="addKebutuhan" class="btn btn-sm btn-primary w-100 mb-3">Tambah Details</button>
                                <button type="submit" class="btn btn-md btn-success w-100">Simpan Data</button>
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
    <script src="{{ asset('assets/js/project2.js') }}"></script>
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

    <!-- Details Kebutuhan -->
<script>
    let idCounter = 1;
    function addKebutuhanrow() {
        const newRow = `
        <div class="content-wrap">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label class="form-label">Jabatan Anggota</label>
                        <select class="form-control" id="jabatan_select" name="jabatan[]">
                            @foreach ($jabatan as $data)
                                <option value="{{$data->name}}">{{$data->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Kebutuhan Anggota</label>
                        <input type="number" name="kebutuhan[]" class="form-control kebutuhan-anggota${idCounter}" id="KebutuhanAnggota${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Gaji Pokok</label>
                        <input type="number" name="p_gajipokok[]" class="form-control allowences${idCounter}" id="GajiPokok${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran BPJS TK</label>
                        <input type="number" name="p_bpjstk[]" class="form-control allowences${idCounter}" id="p_BPJS_TK${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran BPJS KS</label>
                        <input type="number" name="p_bpjs_ks[]" class="form-control allowences${idCounter}" id="bpjsks${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran THR</label>
                        <input type="number" name="p_thr[]" class="form-control allowences${idCounter}" id="thr${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Tunjangan Kerja</label>
                        <input type="number" name="p_tkerja[]" class="form-control allowences${idCounter}" id="kerja${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Tunjangan Seragam</label>
                        <input type="number" name="p_tseragam[]" class="form-control allowences${idCounter}" id="seragam${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Tunjangan Lain Lain</label>
                        <input type="number" name="p_tlain[]" class="form-control allowences${idCounter}" id="lainlain${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Training</label>
                        <input type="number" name="p_training[]" class="form-control allowences${idCounter}" id="training${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Operasional</label>
                        <input type="number" name="p_operasional[]" class="form-control allowences${idCounter}" id="operasional${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Membership Plan</label>
                        <input type="number" name="p_membership[]" class="form-control" id="MemberShipAwal${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Real Deduction</label>
                        <input type="number" name="r_deduction[]" class="form-control" id="real_deduction${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Project Deduction</label>
                        <input type="number" name="p_deduction[]" class="form-control" id="project-deduction${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="card-header mb-2">
                <h4>Total Details</h4>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Penawaran Gapok</label>
                        <input type="number" name="tp_gapok[]" class="form-control" id="TotalGapok${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total BPJS TK</label>
                        <input type="number" name="tp_bpjstk[]" class="form-control" id="TotalBPJSTK${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total BPJS KS</label>
                        <input type="number" name="tp_bpjsks[]" class="form-control" id="TotalBPJSKS${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total THR</label>
                        <input type="number" name="tp_thr[]" class="form-control" id="totalthr${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Tunjangan Kerja</label>
                        <input type="number" name="tp_tunjangankerja[]" class="form-control" id="totalkerja${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Tunjangan Seragam</label>
                        <input type="number" name="tp_tunjanganseragam[]" class="form-control" id="TotalTSeragam${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Tunjangan Lainnya</label>
                        <input type="number" name="tp_tunjanganlainnya[]" class="form-control" id="TotalTLainnya${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Training</label>
                        <input type="number" name="tp_training[]" class="form-control" id="TotalTraining${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Operasional</label>
                        <input type="number" name="tp_operasional[]" class="form-control"id="TotalOperasional${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total PPN</label>
                        <input type="number" name="tp_ppn[]" class="form-control" id="TotalPPN${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total PPH</label>
                        <input type="number" name="tp_pph[]" class="form-control" id="TotalPPH${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Total Cashin</label>
                        <input type="number" name="tp_cashin[]" class="form-control" id="TotalCashin${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Keterangan</label>
                        <input type="number" name="kebutuhan[]" class="form-control" id="Keterangan${idCounter}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Total</label>
                        <input type="number" name="tp_total[]" class="form-control" id="penawaran_total${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Penawaran Total Membership Plan</label>
                        <input type="number" name="tp_membership[]" class="form-control" id="TotalMembership${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Bulanan</label>
                        <input type="number" name="tp_bulanan[]" class="form-control" id="rate_bulan${idCounter}" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-3">
                        <label for="" class="form-label">Rate Harian</label>
                        <input type="number" name="rate_harian[]" class="form-control" id="rate_harian${idCounter}" required>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <button type="button" class="btn btn-danger btn-sm w-100" onclick="removeProductRow(this)">Hapus</button>
                </div>
            </div>
        </div>
        `;
        document.querySelector('#KebutuhanDetails').insertAdjacentHTML('beforeend', newRow);
        idCounter++;
    }

    function removeProductRow(button) {
        const row = button.closest('.content-wrap');  // Ubah dari 'tr' menjadi '.row'
        row.remove();
    }

    document.getElementById('addKebutuhan').addEventListener('click', addKebutuhanrow);
</script>

<!-- Hitungan -->

@endpush