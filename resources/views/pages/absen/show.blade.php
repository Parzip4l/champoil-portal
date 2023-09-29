@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
@endpush

@section('content')
<nav class="page-breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Additional Information</a></li>
    <li class="breadcrumb-item active" aria-current="page">Absensi</li>
  </ol>
</nav>
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
<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <div class="head-card d-flex justify-content-between mb-3">
            <h6 class="card-title align-self-center mb-0">Employees</h6>
            <a class="btn btn-primary d-flex align-items-center" href="{{ route('karyawan.create') }}"><i data-feather="plus" class="icon-sm me-2"></i> <span class="">Tambah Karyawan</span></a>
        </div>
        <hr>
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>Tanggal</th>
                <th>Absen Masuk</th>
                <th>Absen Keluar</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1;
                @endphp
              <tr>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script>
    $(document).ready(function() {
    var table = $('#example').DataTable( {
        scrollY:        "300px",
        scrollX:        true,
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   {
            left: 1,
            right: 1
        }
    } );
} );
  </script>
@endpush