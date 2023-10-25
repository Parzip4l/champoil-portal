@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-4 col-xl-4 mx-auto">
      <div class="card">
        <div class="row">
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <a href="#" class="noble-ui-logo d-block mb-2 text-center">TRUE<span>ST</span></a>
              <h5 class="text-muted fw-normal mb-4 text-center">Silahkan Masukan Email dan Password untuk masuk ke dalam portal.</h5>
              <form class="forms-sample" action="{{url('login/proses')}}" method="POST">
              @csrf
              @if(Session::has('error'))
                  <div class="alert alert-danger">
                      {{ Session::get('error') }}
                  </div>
              @endif
                <div class="mb-3">
                  <label for="userEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="userEmail" placeholder="Email" name="email" required>
                </div>
                <div class="mb-3">
                  <label for="userPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" id="passwordInput" autocomplete="current-password" placeholder="Password" name="password" required>
                </div>
                <div class="form-check mb-3">
                  <input type="checkbox" class="form-check-input" id="authCheck">
                  <label class="form-check-label" for="authCheck">
                    Show Password
                  </label>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary me-2 mb-2 mb-md-0 w-100 btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="log-in"></i>
                    Login
                  </button>
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
  const authCheck = document.getElementById('authCheck');
  const passwordInput = document.getElementById('passwordInput');

  authCheck.addEventListener('change', function() {
    if (authCheck.checked) {
      passwordInput.type = 'text';
    } else {
      passwordInput.type = 'password';
    }
  });
</script>
@endpush