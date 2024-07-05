@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
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
    <div class="card custom-card2">
        <div class="card-header">
            <div class="head-card d-flex justify-content-between">
                <div class="header-title align-self-center">
                    <h6 class="card-title align-self-center mb-0">Update News</h6>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="form-tambah-news">
                <form action="{{route('news.update',$news->id )}}" method="POST" enctype="multipart/form-data">
                    @method('put')
                    @csrf
                    <div class="form-group mb-2">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" name="judul" value="{{$news->judul}}" required placeholder="Judul">
                    </div>
                    <div class="form-group mb-2">
                        <label for="excerpt" class="form-label">Excerpt</label>
                        <input type="text" class="form-control" name="excerpt" value="{{$news->excerpt}}" required placeholder="Deskripsi Singkat">
                    </div>
                    <div class="form-group mb-2">
                        <label for="excerpt" class="form-label">Konten</label>
                        <textarea class="form-control" name="konten" id="tinymceExample" rows="10">{{$news->konten}}</textarea>
                    </div>
                    <div class="form-group mb-2">
                        <img id="imagePreview" src="" alt="Image Preview" style="max-width: 200px; display: none;">
                    </div>
                    <div class="form-group mb-2">
                        <label for="judul" class="form-label">Cover</label>
                        <input type="file" class="form-control" name="featuredimage" id="featuredimage" required onchange="previewImage(event)">
                    </div>
                    <div class="form-group mb-2">
                        <button class="btn btn-primary w-100" type="submit">Update News</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>
</div>

@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="{{ asset('assets/plugins/tinymce/tinymce.min.js') }}"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
  <script src="{{ asset('assets/js/tinymce.js') }}"></script>
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
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('imagePreview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush