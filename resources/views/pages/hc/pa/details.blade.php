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
            <a href="{{url('performance-appraisal/my-performance')}}" class="d-flex color-custom">
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
            <form action="" method="POST">
                @csrf
                <div class="header-data-pa mb-2">
                    <h5>Performance Appraisal</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-2">
                            <label for="Periode">Periode</label>
                            <select name="periode" id="" class="form-control" disabled>
                                <option value="JANUARI - JUNE" {{ $performance->periode == 'JANUARI - JUNE' ? 'selected' : '' }}>JANUARI - JUNE</option>
                                <option value="JULY - DESEMBER" {{ $performance->periode == 'JULY - DESEMBER' ? 'selected' : '' }}>JULY - DESEMBER</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="Periode">Tahun</label>
                            <select name="tahun" id="" class="form-control" disabled>
                                <option value="2024" {{ $performance->tahun == '2024' ? 'selected' : '' }}>2024</option>
                                <option value="2025" {{ $performance->tahun == '2025' ? 'selected' : '' }}>2025</option>
                                <option value="2026" {{ $performance->tahun == '2026' ? 'selected' : '' }}>2026</option>
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="Employee">Karyawan</label>
                            <input type="text" name ="name" class="form-control" value="{{$performance->name}}"disabled>
                            <input type="hidden" name="nik" class="form-control" value="{{$performance->nik}}">
                        </div>
                        @if($performance->approve_byemployee === 'false')
                            <a href="{{route('approve.Mypa', $performance->id)}}" class="btn btn-sm btn-success">Tanda Tangan</a>
                        @else

                        @endif
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
                            @php
                                $detailsdata = json_decode($performance->detailsdata);
                            @endphp

                            <tbody>
                                @foreach($detailsdata as $item)
                                    <tr>
                                        <td colspan="5" style="background-color: #f0f0f0;">
                                            <b>{{ $item->kategori }}</b>
                                            <input type="hidden" name="kategoriname[]" value="{{ $item->kategori }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="max-width: 200px;">
                                            <div class="form-group" style="white-space: normal;">
                                                <div class="faktor-name mx-2">
                                                    <p>{{ $item->name }}</p>
                                                    <input type="hidden" name="name[]" value="{{ $item->name }}">
                                                    <input type="hidden" name="id[]" value="{{ $item->id }}">
                                                </div>
                                                <div class="deskripsi-faktor mx-2">
                                                    <p class="text-muted">{{ $item->deskripsi }}</p>
                                                    <input type="hidden" name="deskripsi[]" value="{{ $item->deskripsi }}">
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            {{ $item->bobot_nilai }}%
                                            <input type="hidden" name="bobot_nilai[]" value="{{ $item->bobot_nilai }}" >
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="nilai[{{ $item->id }}]" max="4" value="{{ $item->nilai }}" oninput="validity.valid||(value=''); hitungTotal();" disabled>
                                        </td>
                                        <td><input type="text" class="form-control" name="nilaifaktor[]" value="{{ $item->bobot_nilai * $item->nilai / 100 }}" readonly disabled></td>
                                        <td><textarea name="keterangan[{{ $item->id }}]" class="form-control" disabled>{{ $item->keterangan }}</textarea></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td><b>Total Nilai </b></td>
                                    <td colspan="2"></td>
                                    <td colspan="2"><input type="text" id="total_nilai" class="form-control" name="nilai_keseluruhan" value="{{ $performance->nilai_keseluruhan }}" readonly disabled></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                    <table id="" class="table">
                            <thead>
                                <tr>
                                    <th>Komentar Atau Masukan</th>
                                    <th>Catatan Target</th>
                                </tr>
                            </thead>
                            <tbody>
                                <td><textarea name="komentar_masukan" id="" class="form-control" disabled>{{ $performance->komentar_masukan }}</textarea></td>
                                <td><textarea name="catatan_target" id="" class="form-control" disabled>{{ $performance->catatan_target }}</textarea></td>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row px-3">
                    <div class="col-md-6">
                        <p class="">Karyawan Yang Dinilai</p>
                            @if($performance->approve_byemployee === 'false')
                             <a href="{{route('approve.Mypa', $performance->id)}}" class="btn btn-sm btn-success mt-5">Tanda Tangan</a>
                                
                            @else
                            <img src="{{ asset('images/approve.png') }}" alt="" class="approved">
                            <h5>{{$performance->name}}</h5>
                            <p class="text-muted">{{ $performance->updated_at->translatedFormat('d M Y') }}</p>
                            @endif
                            
                    </div>
                    <div class="col-md-6">
                        <p class="">Karyawan Yang Menilai</p>
                        <img src="{{ asset('images/approve.png') }}" alt="" class="approved">
                        <h5>{{$performance->created_by}}</h5>
                        <p class="text-muted">{{ $performance->created_at->translatedFormat('d M Y') }}</p>
                    </div>
                </div>
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
<style>
    img.approved {
    max-width: 20%;
    margin-left: -10px;
}
</style>
@endpush