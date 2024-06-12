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

        <form class="forms-sample" action="{{ route('submit-dmaic') }}" method="post">
          <div class="mb-3">
            <label for="exampleInputUsername1" class="form-label">NAMA</label>
            <select name="nama" class="form-control select2" id="nama" required>
                <option value="">-- PILIH --</option>
                @if($employee)
                    @foreach($employee as $empl)
                        <option value="{{ $empl->id }}">{{ $empl->nama }}</option>
                    @endforeach
                @endif
            </select>
          </div>
          <div class="mb-3">
            <label for="exampleInputEmail1" class="form-label">PROJECT</label>
            <select name="project" class="form-control select2" id="project" required>
                <option value="">-- PILIH --</option>
                @if($project)
                    @foreach($project as $pro)
                        <option value="{{ $pro->id }}">{{ $pro->name }}</option>
                    @endforeach
                @endif
            </select>
          </div>
            @if($points)
                    @php 
                        $no=1;
                    @endphp
                    @foreach($points as $point)
                        <div class="mb-3" >
                            <label  class="form-label">{{ $no }}. {{ $point->point_name }}</label>
                            <input type="hidden" name="dmaic_point[]" value="{{ $point->id }}">
                            <textarea class="form-control" id="content{{$no}}" placeholder="Enter the Description" rows="5" name="dmaic_value[]" required></textarea>
                        </div>
                        @php 
                            $no++;
                        @endphp
                    @endforeach
                        <div class="mb-3" >
                            <label  class="form-label">{{ $no }}. Category</label>
                            <select name="category" class="form-control select2" id="category" required>
                                <option value="">-- PILIH --</option>
                                @if($category)
                                    @foreach($category as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
            @endif

          <button type="submit" class="btn btn-primary me-2">Submit</button>
        </form>

      </div>
    </div>
  </div>
 
</div>

<!-- End -->
@endsection
@push('custom-scripts')
<script>
    ClassicEditor.create( document.querySelector( '#content1' ) )
        .catch( error => {
            console.error( error );
        } );

        ClassicEditor.create( document.querySelector( '#content2' ) )
        .catch( error => {
            console.error( error );
        } );
        ClassicEditor.create( document.querySelector( '#content3' ) )
        .catch( error => {
            console.error( error );
        } );
        ClassicEditor.create( document.querySelector( '#content4' ) )
        .catch( error => {
            console.error( error );
        } );
        ClassicEditor.create( document.querySelector( '#content5' ) )
        .catch( error => {
            console.error( error );
        } );
        ClassicEditor.create( document.querySelector( '#content6' ) )
        .catch( error => {
            console.error( error );
        } );
</script>
@endpush
