@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card custom-card2">
            <div class="card-header d-flex justify-content-between">
                <div class="judul align-self-center">
                    <h5 class="align-self-center">Preview Schedule {{ $file_name }}</h5>
                    <p>Penusan periode : {{ strtoupper($periode) }} </p>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    
                    <ol type="1" id="messages">
                        
                    </ol>
                    <form id="form_data" action='{{ route("post-data-schedule") }}' method="POST">
                        @csrf
                    <table id="" class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Schedule Code</th>
                                <th>Project</th>
                                <th>Employee</th>
                                <th>Tanggal</th>
                                <th>Shift</th>
                                <th>Periode</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($records) && count($records) > 0)
                            @php 
                                $no = 0;
                                $color = 'red';
                                $msg = [];
                            @endphp
                            @foreach($records as $row)
                                @if($no > 0 && $row['D'] != NULL)
                                    <input type="hidden" name="schedule_code[]" value="{{$row['A']}}">
                                    <input type="hidden" name="project[]" value="{{$row['B']}}">
                                    <input type="hidden" name="employee[]" value="{{$row['C']}}">
                                    <input type="hidden" name="tanggal[]" value="{{$row['D']}}">
                                    <input type="hidden" name="shift[]" value="{{$row['E']}}">
                                    <input type="hidden" name="periode[]" value="{{$row['F']}}">
                                    
                                    <tr id="data-{{$no}}">
                                        <td>{{ $no }}</td>
                                        <td>{{ $row['A'] }}</td>
                                        <td>{{ @project_byID($row['B'])->name }}</td>
                                        <td>{{ optional(karyawan_bynik($row['C']))->nama }}</td>
                                        <td>{{ $row['D'] }}</td>
                                        <td>{{ $row['E'] }}</td>
                                        <td>{{ $row['F'] }}</td>
                                    </tr>
                                @endif
                                @php 
                                    $no++;
                                @endphp
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <button type="submit" id="submit" class="btn btn-success mt-3">Submit</button>
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
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
    <script>
        $( document ).ready(function() {
            var records = @json($records);
            var periode = '{{ strtoupper($periode) }}';
            var no = 0;
            var messages = [];
            $.each(records, function(index, row) {
                if (no > 0) {
                    var color = 'red';
                    if(row.D !== NULL){
                        if (row.F === periode) {
                            color = "green";
                        } else {
                            $('#data-'+no).css('background-color','red');
                            messages.push("Nama periode tidak sama, pada baris ke: " + no);
                        }
                        if (row.B) {
                            color = "green";
                        } else {
                            $('#data-'+no).css('background-color','red');
                            messages.push("Nama Project tidak boleh kosong, pada baris ke: " + no);
                        }
                    }
                }
                no++;
            });

            $.each(messages, function(index, message) {
                $('#messages').append(`<li style='color:red'>${message}</li>`);
            });

            var error_line = $('#messages li').length;
            console.log(error_line);
            if(error_line==0){
                $('#submit').show();
            }else{
                $('#submit').hide();
            }

            
        });
    </script>

@endpush
