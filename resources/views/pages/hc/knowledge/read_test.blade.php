@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<!-- {{ $file_module }} -->
<!-- http://data.cityservice.co.id/aFyPRgtGlU3tLNb1kWvyqnZ6r3S3e3aMq14Uqd6G.pdf#toolbar=0 -->

    <iframe src="{{ $file_module }}#toolbar=0" width="400" height="800"></iframe>
    <!-- <a href="{{ route('kas/user.test', ['id' => $id_module]) }}"  class="btn btn-primary btn-sm">Lanjut Test</a> -->
    <a href="javascript:void(0)" id="goto_test"  class="btn btn-primary btn-sm">Lanjut Test</a>
<!-- End -->
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
    document.getElementById("goto_test").addEventListener("click", function() {
        // Use SweetAlert2 to display a custom alert
        Swal.fire({
          title: 'Jika Anda Melanjutkan untuk test anda tidak dapat mengakses module kembali,?',
          showDenyButton: true,
          showCancelButton: false,
          confirmButtonText: 'Yes',
          denyButtonText: `No`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            window.location.href = "/kas/user.test/<?php echo $id_module ?>"
          } else if (result.isDenied) {
            Swal.fire('Changes are not saved', '', 'info')
          }
        })
    });


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
@endpush