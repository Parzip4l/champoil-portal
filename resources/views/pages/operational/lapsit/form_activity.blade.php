@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <style>
    .loading-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1050;
        display: flex;
        justify-content: center;
        align-items: center;
    }
  </style>
@endpush

@php 
    $employee = \App\Employee::where('nik', Auth::user()->name)->first();
    $feedback = \App\Feedback::where('name', Auth::user()->name)->first();
    $dataLogin = json_decode(Auth::user()->permission);
    $user = Auth::user();
@endphp

@section('content')
<form id="activity" method="POST" enctype="multipart/form-data" class="container mt-5">
  <div class="card">
      <div class="card-header"></div>
      <div class="card-body">
          <div class="mb-3">
              <label for="camera" class="form-label">Take a Picture:</label>
              <input type="file" class="form-control" id="camera" name="image" accept="image/*" capture="camera" required>
          </div>

          <!-- Hidden input fields for the unix_code and employee -->
          <input type="hidden" id="unix_code" name="unix_code">
          <input type="hidden" id="employee" name="employee" value="{{ $employee->nik }}">

          <div class="mb-3">
              <label for="remarks" class="form-label">Remarks</label>
              <textarea class="form-control" name="remarks" id="remarks" style="height:150px"></textarea>
          </div>
      </div>
      <div class="card-footer">
          <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
      </div>
  </div>
</form>

<!-- Loading Backdrop -->
<div id="loadingBackdrop" class="loading-backdrop" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/qrious/dist/qrious.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endpush

@push('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const unixCode = window.location.pathname.split('/').pop();
        document.getElementById('unix_code').value = unixCode;

        const submitBtn = document.getElementById('submitBtn');
        const loadingBackdrop = document.getElementById('loadingBackdrop');

        submitBtn.addEventListener('click', function() {
            // Show the loading backdrop
            loadingBackdrop.style.display = 'flex';

            const formData = new FormData();
            formData.append('images', document.getElementById('camera').files[0]);
            formData.append('unix_code', unixCode);
            formData.append('employee', document.getElementById('employee').value);
            formData.append('remarks', document.getElementById('remarks').value);

            axios.post('/api/v1/lapsit-activity', formData)
                .then(response => {
                    loadingBackdrop.style.display = 'none'; // Hide the loading backdrop
                    Swal.fire('Success', 'Activity submitted successfully!', 'success');
                })
                .catch(error => {
                    loadingBackdrop.style.display = 'none'; // Hide the loading backdrop
                    Swal.fire('Error', 'Failed to submit the activity.', 'error');
                });
        });
    });
</script>
@endpush
