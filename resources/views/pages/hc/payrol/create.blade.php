@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush
@php
    $user = Auth::user();
    $karyawanLogin = \App\Employee::where('nik', $user->employee_code)
        ->select('unit_bisnis','organisasi')
        ->first();
@endphp
@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card custom-card2">
        <div class="card-header">
            <h4 class="card-title mb-0">Payrol Component</h4>
        </div>
      <div class="card-body">
        <form method="POST" action="{{ route('payrol-component.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label for="" class="form-label">Karyawan</label>
                    <select class="js-example-basic-single form-select" data-width="100%" name="employee_code" required>
                        @foreach($employee as $karyawan)
                            <option value="{{$karyawan->nik}}">{{$karyawan->nama}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="title mt-2 mb-2">
                    <h5>Allowence</h5>
                    <hr>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Gaji Pokok</label>
                    <input type="number" name="basic_salary" class="form-control allowance" required>   
                </div>
                @foreach($allowence as $dataallowence)
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">{{$dataallowence->name}}</label>
                    <input type="number" name="allowances[{{$dataallowence->id}}][]" class="form-control allowance" required>   
                </div>
                @endforeach
                <div class="title mt-2 mb-2">
                    <h5>Deduction</h5>
                    <hr>
                </div>
                @foreach($deduction as $datadeduction)
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">{{$datadeduction->name}}</label>
                    <input type="number" name="deductions[{{$datadeduction->id}}][]" class="form-control deduction" id="t_deduction" required>   
                </div>
                @endforeach
            </div>
          <button class="btn btn-primary mt-4" type="submit">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('plugin-scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/select2.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/form-validation.js') }}"></script>
  <script src="{{ asset('assets/js/bootstrap-maxlength.js') }}"></script>
  <script src="{{ asset('assets/js/inputmask.js') }}"></script>
  <script src="{{ asset('assets/js/select2.js') }}"></script>
@endpush