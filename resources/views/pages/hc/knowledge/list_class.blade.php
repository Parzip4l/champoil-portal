@extends('layout.master')
<style>

</style>
@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="log-absen-today mt-2">
    <div class="card ">
        <div class="card-header text-center bg-warning">
            <h5>Test yang sudah dikerjakan</h5>   
        </div>
    </div>
</div>
<div class="log-absen-today mt-2" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);">
    <div class="card ">
        <div class="card-body">
              @foreach($test_finis as $row)
                <small class="text-muted">Tanggal : <?php echo date('d F Y H:i:S',strtotime($row->updated_at)) ?></small>
                <p>{{ $row->title }} - {{ $row->total_point}} <i class="me-2 icon-lg" style="color:yellow" data-feather="star"></i></p>
              @endforeach
        </div>
    </div>
</div>

<div class="log-absen-today mt-2">
    <div class="card ">
        <div class="card-header text-center bg-warning">
            <h5>Test Baru</h5>   
        </div>
    </div>
</div>
<div class="log-absen-today mt-2" style="box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.5);">
    <div class="card ">
        <div class="card-body">
              @foreach($asign_test as $row_asign_test)
                @if($row_asign_test->metode_training == "Online")
                  <a href="{{ route('read_test', ['id' => $row_asign_test->id_test]) }}" class="btn btn-sm btn-primary">Lihat Test</a>
                @else
                  @if($row_asign_test->start_class == 1)
                      <a href="{{ route('read_test', ['id' => $row_asign_test->id_test]) }}" class="btn btn-sm btn-primary">Lihat Test</a>
                  @else
                      <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Class Offline
                      </button>

                      <!-- Modal -->
                      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="exampleModalLabel">Informasi Class</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <p>Materi : PTSD</p>
                              <?php
                                  $data = json_decode($row_asign_test->notes_training);
                                  foreach ($data as $key => $value) {
                                      if($key=="tanggal"){
                                          echo "<p>".ucfirst($key) . ': ' . date('d F Y',strtotime($value)) . '<p>';
                                      }else{
                                          echo "<p>".ucfirst($key) . ': ' . $value . '<p>';
                                      }
                                    
                                  }
                              ?>
                            </div>
                            <div class="modal-footer">
                              
                            </div>
                          </div>
                        </div>
                      </div>
                    @endif
                @endif
              @endforeach

        </div>
    </div>
</div>
   
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