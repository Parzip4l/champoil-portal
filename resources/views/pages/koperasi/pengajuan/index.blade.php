@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<!-- Top Bar -->
<div class="row mb-5 mobile">
    <div class="topbar-wrap d-flex justify-content-between">
        <div class="arrow-back">
            <a href="{{url('koperasi-page')}}" class="d-flex color-custom">
                <i class="me-2 icon-lg" data-feather="chevron-left"></i>
                <h5 class="align-self-center">Koperasi</h5>
            </a>
        </div>
        <div class="logout-button">
            <form action="{{ route('logout') }}" method="POST" id="logout_admin">
                @csrf
                <a href="#" class="text-body ms-0 d-flex" onClick="submitForm()">
                    <h5 class="text-danger me-2 align-self-center">Log Out</h5>
                    <i class="me-2 icon-md text-danger" data-feather="log-out"></i>
                </a>
            </form>
        </div>
    </div>
</div>
<!-- Profile Card -->
<div class="row">
    <div class="col-md-12">
        <div class="card custom-card2 mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="content-wrap-employee-card d-flex justify-content-between mb-5">
                        <div class="content-left align-self-center">
                            <div class="employee-name mb-1">
                                <h5 class="color-custom-secondary text-uppercase">{{ $employee->nama }}</h5>
                            </div>
                            <div class="employee-title-job">
                                <p class="color-custom-secondary">{{ $employee->jabatan }}</p>
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
                                <p class="color-custom">Employee ID</p>
                            </div>
                            <div class="employee-name mb-1">
                                <h5 class="color-custom-secondary text-uppercase">{{ $employee->nik }}</h5>
                            </div>
                        </div>
                        <div class="content-right">
                            <div class="employee-title-job text-right color-custom">
                                <p class="color-custom">Division</p>
                            </div>
                            <div class="employee-name mb-1">
                                <h5 class="text-uppercase color-custom-secondary">{{ $employee->organisasi }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End -->

<!-- Koperasi Page -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card custom-card2 mb-3">
            <div class="card-body">
                <div class="title-saving d-flex justify-content-between mb-2">
                    <p class="text-muted mb-1">Max Limit Pinjaman</p>
                    <h3 id="maxLimit">Rp {{ number_format($limitpinjaman, 0, ',', '.') }}</h3>
                </div>
                <div class="form-pengajuan">
                    <form action="{{route('pengajuan-pinjaman.store')}}" method="post">
                        @csrf 
                        <div class="form-group mb-2">
                            <label for="jumlah" class="form-label">Masukan Jumah Pinjaman</label>
                            <input type="number" id="jumlahPinjaman" class="form-control" name="amount" required>
                        </div>
                        <a href="" data-bs-target="#ModalPemberitahuan" id="cekSaldoButton" data-bs-toggle="modal" class="btn btn-primary w-100">Ajukan Pinjaman</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pemberitahuan -->
<div class="modal fade" id="ModalPemberitahuan" tabindex="-1" aria-labelledby="ModalPemberitahuan" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalPemberitahuan">Kalkulasi Pinjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
            </div>
            <div class="modal-body">
                <div class="data-keterangan d-flex justify-content-between mb-2">
                    <p>Saldo Yang Diajukan</p>
                    <h5 id="saldoDiajukan"></h5>
                </div>
                <div class="data-keterangan d-flex justify-content-between mb-2">
                    <p>Biaya Membership</p>
                    <h5> {{$koperasi->membership}}%</h5>
                </div>
                <div class="data-keterangan d-flex justify-content-between mb-2">
                    <p>Biaya Merchendise</p>
                    <h5>{{$koperasi->merchendise}}%</h5>
                </div>
                <div class="data-keterangan d-flex justify-content-between mb-2">
                    <h6 >Total</h6>
                    <h5 id="hasilKalkulasi"></h5>
                </div>
                <hr>
                <div class="data-keterangan d-flex justify-content-between mb-3">
                    <p class="text-muted">Jumlah Tenor</p>
                    <p class="text-muted">{{$koperasi->tenor}} Bulan</p>
                </div>
                <div class="data-keterangan d-flex justify-content-between mb-1">
                    <h6 >Jumlah Yang Harus Dibayarkan /Bulan</h6>
                </div>
                <h4 class="text-right mb-4" id="pembayaranBulanan"></h4>
                <form action="{{route('pengajuan-pinjaman.store')}}" method="post">
                    @csrf 
                    <input type="hidden" id="instalment" name="instalment">
                    <input type="hidden" id="amount" name="amount">
                    <input type="hidden" id="tenor" name="tenor">
                    <button type="submit" class="btn btn-primary w-100">Ajukan Pinjaman</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script src="{{ asset('assets/js/password.js') }}"></script>
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
    document.addEventListener('DOMContentLoaded', function() {
        const maxLimitText = document.getElementById('maxLimit').innerText;
        const maxLimit = parseInt(maxLimitText.replace(/[^0-9]/g, '')); // Remove 'Rp ' and ',' to get integer value
        const inputJumlahPinjaman = document.getElementById('jumlahPinjaman');
        const saldoDiajukan = document.getElementById('saldoDiajukan');
        const hasilKalkulasi = document.getElementById('hasilKalkulasi');
        const cekSaldoButton = document.getElementById('cekSaldoButton');

        inputJumlahPinjaman.addEventListener('input', function() {
            let inputValue = parseInt(this.value);

            if (inputValue > maxLimit) {
                this.value = maxLimit;
                Swal.fire({
                    icon: 'warning',
                    title: 'Limit Tercapai',
                    text: 'Jumlah pinjaman tidak boleh melebihi ' + maxLimitText,
                });
            }
        });

        cekSaldoButton.addEventListener('click', function() {
            let inputValue = parseInt(inputJumlahPinjaman.value);
            saldoDiajukan.innerText = 'Rp ' + inputValue.toLocaleString('id-ID');
            
            // Kalkulasi berdasarkan data yang kamu punya
            let persentaseMembership = parseFloat("{{$koperasi->membership}}") / 100;
            let persentaseMerchandise = parseFloat("{{$koperasi->merchendise}}") / 100;
            let totalPersentase = persentaseMembership + persentaseMerchandise;

            let kalkulasi = inputValue * totalPersentase;
            let TotalPinjaman = inputValue + kalkulasi;

            hasilKalkulasi.innerText = 'Rp ' + TotalPinjaman.toLocaleString('id-ID');

            // Hitung pembayaran bulanan berdasarkan tenor
            let tenor = parseInt("{{$koperasi->tenor}}");
            let pembayaranPerBulan = Math.round(TotalPinjaman / tenor);
            document.getElementById('instalment').value = pembayaranPerBulan;
            document.getElementById('amount').value = TotalPinjaman;
            document.getElementById('tenor').value = tenor;
            pembayaranBulanan.innerText = 'Rp ' + pembayaranPerBulan.toLocaleString('id-ID');
        });
    });
</script>
<!-- Clear Cache -->
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

    document.getElementById('clear-cache-link').addEventListener('click', function(event) {
        event.preventDefault();
        fetch('/clear-cache')
            .then(response => response.text())
            .then(data => {
                Swal.fire({
                    icon: 'success', // Ganti menjadi 'success' atau 'error' berdasarkan hasil permintaan
                    title: 'Clear Cache',
                    text: data, // Menampilkan pesan hasil dalam SweetAlert
                });
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
@endpush