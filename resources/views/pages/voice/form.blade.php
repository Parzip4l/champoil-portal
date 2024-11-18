@extends('layout.master2')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">
  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-8 col-sm-12 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <h3 class="text-muted fw-normal mb-4 text-center">Voice Of Guardians</h3><hr/>
              <form class="forms-sample" id="form-voice" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Nama Lengkap : *</label>
                        <input type="text" name="nama" class="form-control" required="">   
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Nomor WA : *</label>
                        <input type="text" name="nomor_wa" class="form-control" required="">   
                    </div>
                    
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Project : *</label>
                        {!! project_all() !!} 
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Messages : *</label>
                        <textarea name="pertanyaan" id="pertanyaan" required="" class="form-control" height="130px"></textarea>
                    </div>
                    <div class="col-md-12 mb-2">
                        <label for="" class="form-label">Upload File : *</label>
                        <input type="file" name="attachment" class="form-control" required="">
                        <input type="hidden" name="status" value="0" class="form-control" required="">
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
            const formData = new FormData($("#form-voice")[0]);

            // Replace this with actual data or get data from form fields
            const employeeData = {
                email: $("input[name='email']").val(), // Replace with correct field name
                nik: $("input[name='nik']").val(),     // Replace with correct field name
                nama: $("input[name='nama']").val()    // Replace with correct field name
            };

            Swal.fire({
                title: "Are you sure?",
                html: `Apakah Data Yakin ?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, proceed!"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make the Axios request to submit the application
                    axios.post('/api/v1/submit-voice', formData, {
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
                            $("#form-voice")[0].reset(); // Reset the form
                        });
                    })
                    .catch(function(error) {
                        console.error(error);
                        Swal.fire({
                            title: "Error!",
                            text: 'There was an error submitting the form.',
                            icon: "error"
                        });
                    });
                }
            });
        });
    });
    
  </script>
  
@endpush
