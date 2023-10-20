@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
<?php 
$currentDateTime = date('Y-m-d H:i:s');

// Add 2 hours to the current date and time
$newDateTime = date('Y-m-d H:i:s', strtotime($currentDateTime) + $durasi_test * 60);
?>
<form action="{{route('knowledge.save_test_user')}}" method="POST" id="form_test" enctype="multipart/form-data">
    @csrf
    <input type="hidden" id="id_module" name="id_module" value="{{ $id_module }}">
    <hr/><div id="countdown"></div><hr/>

    <div id="soal_test">
        <ol type="1">
          <?php $no=0 ?>
          @foreach($result as $row)
          <div class="row mb-3">
            <li>{{ $row->master_soal }}</li>
            <?php $no2="A"; ?>
            @foreach($row->jawaban as $jawaban)
                    <div class="form-check mt-3">
                      <input name="test_{{$no}}" 
                              class="form-check-input" 
                              type="radio" 
                              value="{{$jawaban->id}}-{{ $jawaban->id_soal }}" 
                              id="{{$jawaban->id}}">
                      <label class="form-check-label" for="{{$jawaban->id}}">
                        {{ $no2 }}. {{ $jawaban->jawaban }}
                      </label>
                    </div>
                <?php $no2++; ?>
            @endforeach
          </div>
          <?php $no++; ?>
          @endforeach
        </ol>
    </div>
    <button class="btn btn-primary w-100" type="submit">Simpan Data</button>
</form>
<!-- http://data.cityservice.co.id/aFyPRgtGlU3tLNb1kWvyqnZ6r3S3e3aMq14Uqd6G.pdf#toolbar=0 -->
   
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
    
  </script>
  <script>
    

    var TargetDate = "<?php echo $newDateTime ?>";
    var BackColor = "palegreen";
    var ForeColor = "navy";
    var CountActive = true;
    var CountStepper = -1;
    var LeadingZero = true;
    var DisplayFormat = "%%H%% Hours, %%M%% Minutes, %%S%% Seconds.";
    var FinishMessage = "It is finally here!";
    var form = document.getElementById("form_test");

    function updateCountdown() {
        var now = new Date();
        var targetDate = new Date(TargetDate);
        var timeDiff = targetDate - now;

        if (timeDiff <= 0) {
            document.getElementById("countdown").innerHTML = FinishMessage;
            form.submit();
            return;
        }

        var seconds = Math.floor((timeDiff / 1000) % 60);
        var minutes = Math.floor((timeDiff / (1000 * 60)) % 60);
        var hours = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);

        if (LeadingZero) {
            hours = (hours < 10) ? "0" + hours : hours;
            minutes = (minutes < 10) ? "0" + minutes : minutes;
            seconds = (seconds < 10) ? "0" + seconds : seconds;
        }

        var countdownText = DisplayFormat.replace("%%H%%", hours).replace("%%M%%", minutes).replace("%%S%%", seconds);
        document.getElementById("countdown").innerHTML = countdownText;
    }

    setInterval(updateCountdown, 1000);
    updateCountdown();

</script>
<script language="JavaScript" src="https://rhashemian.github.io/js/countdown.js"></script>
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