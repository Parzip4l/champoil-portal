<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
    
    <!-- Header with logo and title in one row using inline CSS -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        
        <div style="text-align: center;">
            
        </div>
    </div>
    <table>
        <tr>
            <td>
                <img src="https://hris.truest.co.id/images/company_logo/hilt2.png" style="width: 200px;" alt="Company Logo">
            </td>
            <td style="text-align:center">
                <h2 style="margin: 0;">DOUBLETREE By HILTON <br/>JAKARTA
                KEMAYORAN</h2>
                <h4 style="margin: 0;">{{ $filter }}</h4>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; border: 1px solid #ddd; font-size: 9px !important;">
        <thead>
            <tr style="background-color: #f2f2f2; text-align: left;">
                <th>NO</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Nama Anggota</th>
                <th>Lokasi</th>
                <th>Finding</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1;
            @endphp
            @foreach($patroli as $row)
                @php 
                    $img = '';
                    if (!empty($row->image) && $row->status == 0) {
                        if (strpos($row->image, 'https://') === 0) {
                            $img = "<img src='" . $row->image . "' style='width:30px;'>";
                        } else {
                            $img = "<img src='https://hris.truest.co.id" . $row->image . "' style='width:30px;'>";
                        }
                    }

                    if($code == 'lapsit'){
                        $employee = karyawan_bynik($row->employee)->nama;
                    }else{
                        $employee = karyawan_bynik($row->employee_id)->nama;
                    }
                @endphp
                <tr style="page-break-inside: avoid;">
                    <td style="padding: 8px; border: 1px solid #ddd;">{{  $no }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{   date('Y-m-d',strtotime($row->created_at)) }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{   date('H:i:s',strtotime($row->created_at)) }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $employee }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $row->judul }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{!! $img !!}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $row->remarks }}</td>
                </tr>
                @php 
                    $no++;
                @endphp
                
            @endforeach
            
        </tbody>
    </table>
</body>
</html>
