@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <h6 class="card-title mb-0">Pengajuan Cicilan</h6>
                    </div>
                       
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table  class="table checkbox-datatable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Pengajuan Cicilan</th>
                                <th>Status Pengajuan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
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
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@endpush

@push('custom-scripts')
<script src="{{ asset('assets/js/data-table.js') }}"></script>
<script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script>
$(document).ready(function() {
    const table = $('.checkbox-datatable').DataTable();

    axios.get('/api/v1/data_pengajuan')
    .then(response => {
        const data = response.data.data;
        data.forEach(pengajuan => {
            table.row.add([
                `<div class="dt-checkbox">
                    <input type="checkbox" name="select_row" value="${pengajuan.id}">
                    <span class="dt-checkbox-label"></span>
                </div>`,
                pengajuan.nama_lengkap+`<br/>Nomor Kontak :${pengajuan.nomor_hp} <br/><a href="{{ asset('${pengajuan.ktp}') }}" target="_blank" class="btn btn-success approve-btn btn-xs">Lihat KTP</a>`,
                pengajuan.tanggal_pengajuan,
                pengajuan.nama_barang,
                pengajuan.status, // This already includes HTML for the badge (e.g., "Approved" or "Rejected")
                `<button class="btn btn-success approve-btn btn-sm" onClick="update_pengajuan(${pengajuan.id},1)">Approve</button>
                 <button class="btn btn-danger reject-btn btn-sm" onClick="update_pengajuan(${pengajuan.id},2)">Reject</button>`
            ]).draw(false);
        });
    })
    .catch(error => {
        console.error('There was an error fetching the data:', error);
    });

});

function update_pengajuan(id, status) {
    axios.post('/api/v1/update_pengajuan', { id, status })
        .then(response => {
            Swal.fire({
                title: "Success!",
                text: `Pengajuan has been ${status === 'approved' ? 'approved' : 'rejected'}.`,
                icon: "success"
            });

            location.reload();

        })
        .catch(error => {
            console.error('There was an error updating the pengajuan:', error);
            Swal.fire({
                title: "Error!",
                text: 'There was an error updating the pengajuan.',
                icon: "error"
            });
        });
}

</script>
@endpush