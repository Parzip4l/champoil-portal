@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

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
        <div class="table-responsive">
          <table id="dataTableExample" class="table">
            <thead>
              <tr>
                <th>#</th>
                <th>Payslip Code</th>
                <th>Payroll Periode</th>
              </tr>
            </thead>
            <tbody>
                @php 
                    $no = 1;
                    
                @endphp
                @foreach ($payslips as $data)
              <tr>
                <td>{{ $no++ }}</td>
                @php
                    $employee = \App\Employee::where('nik', $data->employee_code)->first();
                @endphp
                @if($employee->organisasi === 'Management Leaders')
                <td><a href="{{route('payslip.show', $data->id)}}">{{ $data->employee_code }}</a></td>
                <td>{{ $data->month }} - {{ $data->year }}</td>
                @endif
                @if($employee->organisasi === 'Frontline Officer')
                <td><a href="{{route('payslip-ns.show', $data->id)}}">{{ $data->employee_code }}</a></td>
                <td>{{ $data->month }} - {{ $data->year }}</td>
                @endif
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  
@endpush

@push('custom-scripts')
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script src="{{ asset('assets/js/data-table.js') }}"></script>
    <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
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
    <script>
        $(function() {
    'use strict'

    if ($(".js-example-basic-single").length) {
        $(".js-example-basic-single").select2();
    }
    if ($(".js-example-basic-multiple").length) {
        $(".js-example-basic-multiple").select2();
    }
    });
    </script>
@endpush