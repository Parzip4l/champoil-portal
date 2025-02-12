@extends('layout.master2')

@php
    $token = last(request()->segments());
@endphp

@section('content')
@if(!empty($token))
  <div class="page-content d-flex align-items-center justify-content-center" style="background-color: #f4f6f9; min-height: 100vh;">
    <div class="row w-100 mx-0 auth-page">
      <div class="col-md-4 col-xl-4 mx-auto">
        <div class="card" style="border-radius: 15px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);">
          <div class="row">
            <div class="col-md-12 ps-md-0">
              <div class="auth-form-wrapper px-4 py-5">
                <a href="#" class="noble-ui-logo d-block mb-2 text-center" style="font-size: 24px; font-weight: bold; color: #424874;">TRUE<span style="color: #576cbc;">ST</span></a>
                <h5 class="text-muted fw-normal mb-4 text-center">Reset Your Password</h5>

                <div class="alert alert-success" id="message-success" style="display: none;"></div>
                <div class="alert alert-danger" id="message-danger" style="display: none;"></div>

                <form class="forms-sample" id="reset-password-form" method="POST">
                  @csrf
                  <input type="hidden" name="token" id="token" value="{{ $token }}">

                  <div class="mb-3">
                    <label for="newPassword" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="newPassword" name="password" placeholder="Enter new password" required style="border-radius: 8px;">
                  </div>

                  <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" placeholder="Confirm new password" required style="border-radius: 8px;">
                  </div>

                  <button type="button" id="resetBtn" class="btn btn-primary w-100" style="border-radius: 10px; background-color: #576cbc; border-color: #424874;">
                      Reset Password
                  </button>
                  
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
@else
  <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
      <h3 class="text-danger">Invalid Token</h3>
  </div>
@endif
@endsection

@push('custom-scripts')
<script>
  $(document).ready(function() {
      $('#resetBtn').on('click', function() {
          const password = $('#newPassword').val();
          const confirmPassword = $('#confirmPassword').val();
          const token = $('#token').val();
          const resetBtn = $('#resetBtn');

          // Validasi input
          if (!password || !confirmPassword) {
              $("#message-danger").show().text("Password tidak boleh kosong.");
              return;
          }
          if (password.length < 8) {
              $("#message-danger").show().text("Password minimal 8 karakter.");
              return;
          }
          if (password !== confirmPassword) {
              $("#message-danger").show().text("Password dan Konfirmasi Password harus sama.");
              return;
          }

          resetBtn.html('Processing...').prop('disabled', true);

          axios.post("{{ url('api/v1/submit-forgot-password') }}", {
              token: token,
              password: password,
              password_confirmation: confirmPassword
          })
          .then(response => {
              if (!response.data.error) {
                  $("#message-success").show().text("Password berhasil diubah. Silakan login.");
                  $("#message-danger").hide();
                  $('#reset-password-form').hide();
              } else {
                  $("#message-danger").show().text(response.data.message);
                  $("#message-success").hide();
              }
          })
          .catch(error => {
              $("#message-danger").show().text(response.data.message);
              $("#message-success").hide();
          })
          .finally(() => {
              resetBtn.html('Reset Password').prop('disabled', false);
          });
      });
  });
</script>
@endpush
