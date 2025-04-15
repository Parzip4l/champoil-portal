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
                            <input 
                            type="text" 
                            id="jumlahPinjaman" 
                            class="form-control" 
                            placeholder="Masukkan jumlah pinjaman" 
                            name="amount" 
                            required 
                            data-max="{{ $limitpinjaman }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="jumlah" class="form-label">Pilih Tenor</label>
                            <select name="tenor" class="form-control" id="">
                                <option value="1">1 Bulan</option>
                                <option value="2">2 Bulan</option>
                                <option value="3">3 Bulan</option>
                            </select>
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
                    <p class="text-muted" id="jumlahTenor"></p>
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
        const saldoDiajukan = document.getElementById('saldoDiajukan');
        const hasilKalkulasi = document.getElementById('hasilKalkulasi');
        const pembayaranBulanan = document.getElementById('pembayaranBulanan');
        const jumlahTenor = document.getElementById('jumlahTenor'); 
        const cekSaldoButton = document.getElementById('cekSaldoButton');
        const selectTenor = document.querySelector('select[name="tenor"]');
        const jumlahPinjamanInput = document.getElementById('jumlahPinjaman');

        // Fungsi untuk mengubah angka ke format Rupiah
        function formatRupiah(angka) {
            return angka.toLocaleString('id-ID');
        }

        // Fungsi untuk menghilangkan format Rupiah sebelum dikalkulasi
        function cleanRupiah(value) {
            return parseInt(value.replace(/[^\d]/g, '')) || 0;
        }

        // Format input otomatis ke Rupiah saat diketik
        jumlahPinjamanInput.addEventListener('input', function() {
            let rawValue = this.value;
            let angka = cleanRupiah(rawValue);
            this.value = angka ? 'Rp ' + formatRupiah(angka) : '';
        });

        cekSaldoButton.addEventListener('click', function() {
            let inputValue = cleanRupiah(jumlahPinjamanInput.value);
            let tenor = parseInt(selectTenor.value);
            let limitPinjaman = 100000000; // Limit pinjaman Rp 100.000.000

            if (inputValue <= 0 || isNaN(inputValue)) {
                Swal.fire({ icon: 'error', title: 'Input Salah', text: 'Masukkan jumlah pinjaman yang benar!' });
                return;
            }

            if (inputValue > limitPinjaman) {
                Swal.fire({ icon: 'error', title: 'Limit Terlampaui', text: 'Maksimal pinjaman adalah Rp 100.000.000!' });
                return;
            }

            if (isNaN(tenor) || tenor <= 0) {
                Swal.fire({ icon: 'error', title: 'Tenor Tidak Valid', text: 'Silakan pilih tenor yang benar!' });
                return;
            }

            saldoDiajukan.innerText = 'Rp ' + formatRupiah(inputValue);
            jumlahTenor.innerText = tenor + ' Bulan'; 

            let membershipPersen = parseFloat("{{$koperasi->membership ?? 0}}") / 100;
            let merchandisePersen = parseFloat("{{$koperasi->merchendise ?? 0}}") / 100;
            let merchandise2FullPersen = parseFloat("{{$koperasi->merchandise2 ?? 0}}") / 100;
            let merchandise2Persen = (merchandise2FullPersen / 3) * tenor;

            let totalPersentase = membershipPersen + merchandisePersen + merchandise2Persen;
            let biayaTambahan = inputValue * totalPersentase;
            let totalPinjaman = inputValue + biayaTambahan;

            hasilKalkulasi.innerText = 'Rp ' + formatRupiah(totalPinjaman);
            let pembayaranPerBulan = Math.round(totalPinjaman / tenor);
            pembayaranBulanan.innerText = 'Rp ' + formatRupiah(pembayaranPerBulan);

            // Kirim nilai dalam format angka biasa (tanpa Rp dan titik)
            document.getElementById('instalment').value = pembayaranPerBulan;
            document.getElementById('amount').value = totalPinjaman;
            document.getElementById('tenor').value = tenor;

            // Debugging Console
            console.log("Membership:", membershipPersen);
            console.log("Merchandise:", merchandisePersen);
            console.log("Merchandise2 Full:", merchandise2FullPersen);
            console.log("Merchandise2 Adjusted:", merchandise2Persen);
            console.log("Total Persentase:", totalPersentase);
            console.log("Biaya Tambahan:", biayaTambahan);
            console.log("Total Pinjaman:", totalPinjaman);
            console.log("Pembayaran Per Bulan:", pembayaranPerBulan);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('jumlahPinjaman');
        const maxLimit = parseInt(input.getAttribute('data-max'));

        input.addEventListener('input', function () {
            let rawValue = input.value.replace(/\D/g, '');
            let value = parseInt(rawValue || 0);

            if (value > maxLimit) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Melebihi Batas',
                    text: `Jumlah pinjaman tidak boleh melebihi Rp ${maxLimit.toLocaleString('id-ID')}`,
                    timer: 2000,
                    showConfirmButton: false
                });

                value = maxLimit;
            }

            // Format ulang ke format rupiah
            input.value = value.toLocaleString('id-ID');
        });
    });
</script>

@endpush