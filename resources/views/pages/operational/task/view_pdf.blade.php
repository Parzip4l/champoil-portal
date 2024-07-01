<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    @if($records)
        @foreach($records as $row)
            <h3 style="text-align:center">{{$row->judul }}</h3>
            <img  style="text-align:center;maargin-bottom:500px;" width="100%" src="{{storage_path('app/public/qrcodes/'.$row->unix_code.'.png')}}">
        @endforeach
    @endif
</body>
</html>
