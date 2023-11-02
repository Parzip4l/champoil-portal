
    <script src="{{ asset('assets/js/qrcode.js') }}"></script>
    <!-- SJIS Support (optional) -->
    <script src="{{ asset('assets/js/qrcode_SJIS.js') }}"></script>

    <div id="qrcode"></div>

    <script>
        var typeNumber = 4;
        var errorCorrectionLevel = 'L';
        var qr = qrcode(typeNumber, errorCorrectionLevel);
        qr.addData('{{ $unix_code }}');
        qr.make();
        document.getElementById('qrcode').innerHTML = qr.createImgTag(6);
    </script>