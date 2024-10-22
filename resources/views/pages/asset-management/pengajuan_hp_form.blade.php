@extends('layout.master2')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-8 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
             
              <h3 class="text-muted fw-normal mb-4 text-center">FORM PENGAJUAN CICILAN</h3><hr/>
              <form class="forms-sample" id="form-pengajuan" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Email : *</label>
                        <input type="email" name="email" class="form-control" required="">   
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Nomor Induk (KTP) : *</label>
                        <input type="number" name="nik" class="form-control" required="">   
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Project : *</label>
                        {!! project_all() !!} 
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Nomor HP : *</label>
                        <input type="text" name="nomor_hp" class="form-control" required="">   
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Foto KTP : *</label>
                        <input type="file" name="ktp" class="form-control" required="">   
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Jenis HP : *</label><br/>
                        {!! BarangCicilan() !!}
                    </div>
                    <a href="javascript:void(0)" class="btn  btn-primary btm-sm" id="submit">Submit</a>
              </form> 
            </div>
          </div>
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
    // Attach click event listener to the submit button
    $("#submit").on('click', function(e) {
        e.preventDefault(); // Prevent default form submission

        // Create a FormData object from the form
        const formData = new FormData($("#form-pengajuan")[0]);

        // Make the Axios request to check NIK
        axios.post('/api/v1/check-nik', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
        .then(function(response) {
            const employeeData = response.data.data;

            // Handle success
            Swal.fire({
                title: "Are you sure?",
                html: `Apakah Data Berikut Sudah Benar?<br/>
                       Email: ${employeeData.email}<br/>
                       NIK: ${employeeData.nik}<br/>
                       Nama: ${employeeData.nama}`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, proceed!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make the Axios request to submit the application
                    axios.post('/api/v1/submit-pengajuan-cicilan', formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                    .then(function(response) {
                        Swal.fire({
                            title: "Success!",
                            text: "Data Berhasil Dikirim",
                            icon: "success"
                        }).then(() => {
                            // Optionally reset the form or redirect
                            $("#form-pengajuan")[0].reset(); // Reset the form
                        });
                    })
                    .catch(function(error) {
                        // Handle error for the second request
                        console.error(error);
                        Swal.fire({
                            title: "Error!",
                            text: 'There was an error submitting the form.',
                            icon: "error"
                        });
                    });
                }
            });
        })
        .catch(function(error) {
            // Handle error for the first request
            console.error(error);
            Swal.fire({
                title: "Error!",
                text: 'There was an error checking the NIK.',
                icon: "error"
            });
        });
    });
});



  </script>
@endpush