<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<style>
    body {
            font-family: 'Arial, sans-serif';
            margin: 0;
            padding: 0;
            background-image: url('{{ storage_path("app/public/background_qr.png") }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            width: 100%;
            height: 100%;
        }
</style>
<body>
    @if($records)
        @foreach($records as $row)
            <h3 style="text-align:center">{{$row->judul }}</h3>
            <img  style="text-align:center;maargin-bottom:500px;" 
                  width="100%" src="{{storage_path('app/public/qrcodes/'.$row->unix_code.'.png')}}">
        @endforeach
    @endif
</body>
</html>
