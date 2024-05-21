@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card custom-card2">
      <div class="card-body">
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h4 class="card-title">Payrol Component</h4>
        <form method="POST" action="{{ route('payrol-component.update', $data->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @php 
                $employee = \App\Employee::where('nik', $data->employee_code)->first();
                $allowences = json_decode($data->allowances);
                $deductions = json_decode($data->deductions);
            @endphp
            <div class="row">
                <div class="col-md-12 mb-2">
                    <label for="" class="form-label">Karyawan</label>
                    <input type="text" class="form-control" value="{{ \App\Employee::where('nik', $data->employee_code)->value('nama') ?? 'Nama tidak ditemukan' }}" readonly>
                    <input type="hidden" class="form-control" name="employee_code" value="{{$data->employee_code}}">
                </div>
                <div class="title mt-2 mb-2">
                    <h5>Allowence</h5>
                    <hr>
                </div>
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">Gaji Pokok</label>
                    <input type="number" name="basic_salary" class="form-control allowance" value="{{$data->basic_salary}}" required>   
                </div>
                @php
                    $dataArray = json_decode($data->allowances, true);
                    $datadeduction = json_decode($data->deductions, true);
                @endphp
                @foreach($dataArray['data'] as $id => $value)
                    <div class="col-md-6 mb-2">
                        <label for="" class="form-label">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Nama tidak ditemukan' }}</label>
                        <input type="number" name="allowances[{{$id}}][]" class="form-control allowance" value="{{$value[0]}}" required>   
                    </div>
                @endforeach
                <div class="title mt-2 mb-2">
                    <h5>Deduction</h5>
                    <hr>
                </div>
                @foreach($datadeduction['data'] as $id => $value)
                <div class="col-md-6 mb-2">
                    <label for="" class="form-label">{{ \App\Payrol\Component::where('id', $id)->value('name') ?? 'Nama tidak ditemukan' }}</label>
                    <input type="number" name="deductions[{{$id}}][]" class="form-control deduction" value="{{$value[0]}}" id="t_deduction" required>   
                </div>
                @endforeach
            </div>
          <button class="btn btn-primary" type="submit">Submit</button>
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