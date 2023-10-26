<canvas id="canvas"></canvas>

<script src="{{ asset('node_modules/qrcode/lib/core/qrcode.js') }}"></script>
<script>
  QRCode.toCanvas(document.getElementById('canvas'), 'sample text', function (error) {
    if (error) console.error(error)
    console.log('success!');
  })
</script>