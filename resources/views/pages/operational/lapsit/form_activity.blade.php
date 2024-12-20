@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
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
      <div class="card-header">

      </div>
      <div class="card-body">
      <div class="mb-3">
          <label for="camera" class="form-label">Take a Picture:</label>
          <input type="file" class="form-control" id="camera" name="image" accept="image/*" capture="camera" required>
      </div>

      <!-- Hidden input fields for the unix_code and employee -->
      <input type="hidden" id="unix_code" name="unix_code">
      <input type="hidden" id="employee" name="employee" value="{{ $employee->nik }}">

      <div class="mb-3">
          <label for="camera" class="form-label">Remarks</label>
          <textarea class="form-control" name="remarks" id="remarks" style="height:150px"></textarea>
      </div>
      </div>
      <div class="card-footer">
      <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
      </div>
  </div>

</form>

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
        // Get the last segment of the URL (assuming the unix_code is the last segment)
        const urlSegments = window.location.pathname.split('/');
        const unixCode = urlSegments[urlSegments.length - 1];

        // Set the unix_code as a hidden field value
        document.getElementById('unix_code').value = unixCode;
        
        // Submit form using Axios when the button is clicked
        document.getElementById('submitBtn').addEventListener('click', function() {
            const formData = new FormData();
            formData.append('images', document.getElementById('camera').files[0]);  // Append image file
            formData.append('unix_code', unixCode);  // Append unix_code
            formData.append('employee', document.getElementById('employee').value);
            formData.append('remarks', document.getElementById('remarks').value);

            // Send POST request with Axios
            axios.post('/api/v1/lapsit-activity', formData)
                .then(response => {
                    alert('Activity submitted successfully!');
                    console.log(response.data);
                })
                .catch(error => {
                    console.error('Error submitting the activity:', error);
                    alert('Failed to submit the activity.');
                });
        });
    });
</script>

@endpush
