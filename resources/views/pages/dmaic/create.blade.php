@extends('layout.master2')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <script src="https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js"></script>
@endpush

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title text-center">FORM DMAIC</h6>

        <form class="forms-sample" id="dmaicForm" method="post" action="{{ route('submit-dmaic') }}">
          @csrf
          <div class="mb-3">
            <label for="nama" class="form-label">NAMA</label>
            <select name="nama" class="form-control select2" id="nama" required>
                <option value="">-- PILIH --</option>
                @foreach($employee as $empl)
                    <option value="{{ $empl->id }}">{{ $empl->nama }}</option>
                @endforeach
            </select>
          </div>
          
          <div class="mb-3">
            <label for="project" class="form-label">PROJECT</label>
            <select name="project" class="form-control select2" id="project" required>
                <option value="">-- PILIH --</option>
                @foreach($project as $pro)
                    <option value="{{ $pro->id }}">{{ $pro->name }}</option>
                @endforeach
            </select>
          </div>

          @php $no = 1; @endphp
          @foreach($points as $point)
            <div class="mb-3">
              <label for="content{{ $no }}" class="form-label">{{ $no }}. {{ $point->point_name }}</label>
              <input type="hidden" name="dmaic_point[]" value="{{ $point->id }}">
              <textarea class="form-control" id="content{{ $no }}" placeholder="Enter the Description" rows="5" name="dmaic_value[]"></textarea>
            </div>
            @php $no++; @endphp
          @endforeach

          <div class="mb-3">
            <label for="category" class="form-label">{{ $no }}. Category</label>
            <select name="category" class="form-control select2" id="category" required>
                <option value="">-- PILIH --</option>
                @foreach($category as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-primary me-2">Submit</button>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection

@push('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editors = [];

        // Initialize CKEditor for each textarea with name 'dmaic_value[]'
        const elements = document.getElementsByName('dmaic_value[]');
        for (let i = 0; i < elements.length; i++) {
            ClassicEditor.create(elements[i])
                .then(editor => {
                    editors.push(editor);
                })
                .catch(error => {
                    console.error(error);
                });
        }

       
    });
</script>
@endpush
