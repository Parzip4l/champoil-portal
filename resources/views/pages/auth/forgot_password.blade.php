@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center" style="background-color: #f4f6f9; min-height: 100vh;">
  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-4 col-xl-4 mx-auto">
      <div class="card" style="border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
        <div class="row">
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2 text-center" style="font-size: 24px; font-weight: bold; color: #424874;">TRUE<span style="color: #576cbc;">ST</span></a>
              <h5 class="text-muted fw-normal mb-4 text-center">Reset Password</h5>
              
            <div class="alert alert-success" id="message-success">
                      
                </div>
              <form class="forms-sample" id="reset-password" method="POST">
                @csrf
                
                <div class="alert alert-danger" id="message-danger">
                      
                </div>
                <div class="mb-3">
                  <label for="userEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="userEmail" placeholder="Email" name="email" required style="border-radius: 8px;">
                  
                </div>
                <div>
                <a type="button" id="submitBtn" class="btn btn-primary me-2 mb-2 mb-md-0 w-100 btn-icon-text bg-custom-biru" style="border-radius:10px; border-color: #424874; background-color: #576cbc;">
                    <i class="btn-icon-prepend" data-feather="mail"></i>
                    Send Reset Link
                </a>
                </div>
                <div class="text-center mt-3">
                  <a href="{{ url('login') }}" class="text-muted" style="text-decoration: none; font-weight: 500;">Back to login</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $("#message-danger").hide();
        $("#message-success").hide();
        $('#submitBtn').on('click', function() {
            const email = $('#userEmail').val().trim();
            const submitBtn = $('#submitBtn');

            // Validasi email dengan regex
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                return;
            }
            if (!emailRegex.test(email)) {
                return;
            }

            // Disable tombol & ubah teks
            submitBtn.html('Processing...').prop('disabled', true);

            axios.post("{{ url('api/v1/forgot-password') }}", {
                email: email
            })
            .then(response => {
                if(!response.data.error){
                    $("#message-success").show();
                    $("#message-success").text("Silahkan  Check Email : "+response.data.message.email+"<br/>Link Hanya Berlaku 5 Menit");
                    $("#message-danger").hide();
                    $("#reset-password").hide();
                }else{
                    $("#message-success").hide();
                    $("#message-danger").show();
                    $("#message-danger").text(response.data.message);
                }
            })
            .catch(error => {

            })
            .finally(() => {
                submitBtn.html('<i class="btn-icon-prepend" data-feather="mail"></i> Send Reset Link')
                         .prop('disabled', false);
            });
        });
    });
</script>

@endpush