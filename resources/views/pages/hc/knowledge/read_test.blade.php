@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf_viewer.css">
@endpush

@section('content')
    <!-- <iframe src="{{ asset('knowledge_test/' . $file_module) }}#toolbar=0&embedded=true" width="400" height="800"></iframe> -->
    <!-- <iframe src="http://data.cityservice.co.id/aFyPRgtGlU3tLNb1kWvyqnZ6r3S3e3aMq14Uqd6G.pdf#toolbar=0&" width="400" height="800"></iframe> -->
      <div id="pdf-container">
        <canvas id="pdf-canvas"></canvas>
      </div>
      <button id="prev-page" class="btn btn-warning btn-sm">Previous Page</button>
      <button id="next-page" class="btn btn-secondary btn-sm">Next Page</button>
      <a href="javascript:void(0)" id="goto_test"  class="btn btn-primary btn-sm">Lanjut Test</a>
<!-- End -->
@endsection

@push('plugin-scripts')
  <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
  <script src="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.js') }}"></script>
  <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.8.335/pdf.min.js"></script>
@endpush

@push('custom-scripts')
  <script src="{{ asset('assets/js/data-table.js') }}"></script>
  <script src="{{ asset('assets/js/sweet-alert.js') }}"></script>
<script>
    $('#goto_test').hide();
    var pdfjsLib = window['pdfjs-dist/build/pdf'];
    var pdfURL = '{{ asset('knowledge_test/' . $file_module) }}';
    var container = document.getElementById('pdf-container');
    var canvas = document.getElementById('pdf-canvas');
    var pdfDoc = null;
    var pageNum = 1;
    var pageRendering = false;
    var pageNumPending = null;

    // Asynchronously load the PDF
    pdfjsLib.getDocument(pdfURL).promise.then(function(pdf) {
        pdfDoc = pdf;
        renderPage(pageNum);
    });

    function renderPage(num) {
        pageRendering = true;
        pdfDoc.getPage(num).then(function(page) {
            var scale = 0.5;
            var viewport = page.getViewport({ scale: scale });
            var context = canvas.getContext('2d');
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            // Clear the canvas
            context.clearRect(0, 0, canvas.width, canvas.height);

            // Render the PDF page on the canvas
            page.render({
                canvasContext: context,
                viewport: viewport
            }).promise.then(function () {
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }

                if (pageNum === pdfDoc.numPages) {
                  $('#goto_test').show();
                }else{
                  $('#goto_test').hide();
                }
            });
        });
    }

    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }

    // Previous page button
    document.getElementById('prev-page').addEventListener('click', function() {
        if (pageNum <= 1) {
            return;
        }
        pageNum--;
        queueRenderPage(pageNum);
    });

    // Next page button
    document.getElementById('next-page').addEventListener('click', function() {
        if (pageNum >= pdfDoc.numPages) {
            return;
            
        }
        pageNum++;
        queueRenderPage(pageNum);
    });
    
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