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
        <div class="card-body">
            <form action="{{route('store.performance')}}" method="POST">
                @csrf
                <div class="header-data-pa mb-2">
                    <h5>Performance Appraisal</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="Periode">Periode</label>
                            <select name="periode" id="" class="form-control">
                                <option value="JANUARI - JUNE">JANUARI - JUNE</option>
                                <option value="JULY - DESEMBER">JULY - DESEMBER</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="Periode">Tahun</label>
                            <select name="tahun" id="" class="form-control">
                                <option value="2024">2024</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="Employee">Karyawan</label>
                            <select name="nik" id="" class="form-control">
                                @foreach($employee as $karyawan)
                                    <option value="{{$karyawan->nik}}">{{$karyawan->nama}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table id="" class="table">
                            <thead>
                                <tr>
                                    <th>Faktor</th>
                                    <th>Bobot</th>
                                    <th>Nilai</th>
                                    <th>Bobot Nilai</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kategoriPa as $kategori)
                                    <tr>
                                        <td colspan="5" style="background-color: #f0f0f0;">
                                            <b>{{ $kategori->name }}</b>
                                            <input type="hidden" name="kategoriname[]" value="{{$kategori->name}}">
                                        </td>
                                    </tr>
                                    @foreach($faktor->where('kategori', $kategori->name) as $dataFaktor)
                                        <tr>
                                            <td style="max-width: 200px;">
                                                <div class="form-group" style="white-space: normal;">
                                                    <div class="faktor-name mx-2">
                                                        <p>{{$dataFaktor->name}}</p>
                                                        <input type="hidden" name="name[]" value="{{$dataFaktor->name}}">
                                                        <input type="hidden" name="id[]" value="{{$dataFaktor->id}}">
                                                    </div>
                                                    <div class="deskripsi-faktor mx-2">
                                                        <p class="text-muted">{{$dataFaktor->deskripsi}}</p>
                                                        <input type="hidden" name="deskripsi[]" value="{{$dataFaktor->deskripsi}}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                {{$dataFaktor->bobot_nilai}}%
                                                <input type="hidden" name="bobot_nilai[]" value="{{$dataFaktor->bobot_nilai}}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" name="nilai[{{ $dataFaktor->id }}]" max="4" oninput="validity.valid||(value=''); hitungTotal();">
                                            </td>
                                            <td><input type="text" class="form-control" name="nilaifaktor[]" readonly></td>
                                            <td><textarea name="keterangan[{{ $dataFaktor->id }}]" class="form-control"></textarea></td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><b>Total Nilai </b></td>
                                    <td colspan="2"></td>
                                    <td colspan="2"><input type="text" id="total_nilai" class="form-control" name="nilai_keseluruhan" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <table id="" class="table">
                            <thead>
                                <tr>
                                    <th>Komentar Atau Masukan</th>
                                    <th>Catatan Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td><textarea name="komentar_masukan" id="" class="form-control"></textarea></td>
                                <td><textarea name="catatan_target" id="" class="form-control"></textarea></td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <button class="btn btn-primary w-100" type="submit">Buat Performance Appraisal</button>
            </form>
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
                const deleteUrl = "{{ route('kategori-pa.destroy', ':id') }}".replace(':id', id);
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let inputs = document.querySelectorAll('input[type="number"]');
        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                let max = parseInt(input.getAttribute('max'));
                if (parseInt(input.value) > max) {
                    input.value = max; // Set nilai kembali ke nilai maksimum jika melebihi
                    // Tambahkan pesan kesalahan atau tindakan tambahan sesuai kebutuhan
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    let inputs = document.querySelectorAll('input[type="number"]');
    
    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            let max = parseInt(input.getAttribute('max'));
            if (parseInt(input.value) > max) {
                input.value = max; // Set nilai kembali ke nilai maksimum jika melebihi
                // Tambahkan pesan kesalahan atau tindakan tambahan sesuai kebutuhan
            }
            updateNilaiFaktor(input); // Panggil fungsi untuk mengupdate nilai faktor setiap kali input berubah
            hitungTotal(); // Panggil fungsi untuk menghitung total nilai setiap kali input berubah
        });
    });
});

function updateNilaiFaktor(input) {
    let bobotNilai = parseFloat(input.parentElement.previousElementSibling.textContent); // Ambil bobot nilai dari elemen sebelumnya
    let nilai = parseFloat(input.value); // Ambil nilai dari input saat ini

    if (!isNaN(bobotNilai) && !isNaN(nilai)) {
        let nilaiFaktor = bobotNilai * nilai / 100; // Hitung nilai faktor
        input.parentElement.nextElementSibling.querySelector('input[name="nilaifaktor[]"]').value = nilaiFaktor.toFixed(2); // Update nilai faktor
    }
}

function hitungTotal() {
    var inputs = document.querySelectorAll('input[type="number"]');
    var bobotElements = document.querySelectorAll("td:nth-child(2)"); // Select bobot columns
    var nilaifaktorElements = document.getElementsByName("nilaifaktor[]");
    var total = 0;
    var totalKategori = {{ $totalKategori }}; // Gunakan total jumlah kategori dari controller

    // Loop melalui semua nilai input dan menjumlahkannya
    inputs.forEach(function(input, index) {
        var nilai = parseFloat(input.value) || 0; // Konversi ke float, default ke 0 jika tidak valid
        var bobot = parseFloat(bobotElements[index].innerText) / 100; // Konversi bobot ke desimal
        var bobotNilai = nilai * bobot;

        nilaifaktorElements[index].value = (bobotNilai).toFixed(2); // Update bidang bobot nilai tanpa perkalian dengan 100
        total += bobotNilai;
    });

    // Bagi total dengan jumlah kategori dan bulatkan menjadi 2 tempat desimal
    var result = Math.round((total / totalKategori) * 100) / 100;

    // Tampilkan hasilnya di input hanya baca
    document.getElementById("total_nilai").value = result.toFixed(2); // Tampilkan hasil dengan 2 tempat desimal
}

</script>
@endpush