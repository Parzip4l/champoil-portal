<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Patroli</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            border: 1px solid #ddd;
            font-size: 9px !important;
        }

        thead tr {
            background-color: #f2f2f2;
            text-align: left;
        }

        td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 9px !important;
        }

        tbody tr {
            page-break-inside: avoid;  /* Prevent page break within a row */
        }

        tbody td {
            page-break-inside: avoid;  /* Prevent page break inside cells */
        }

        hr {
            border: 1px solid #ddd;
        }
        @page {
            margin-top:120px; /* No margin for the first page */
        }
    </style>
</head>
<body>
    
    <img src="https://hris.truest.co.id/images/company_logo/hilt2.png">
    <h2 style="text-align:center">{{ $project }}</h2>
    <h4 style="text-align:center">{{ $filter }}</h4>
    <table>
        <thead>
            <tr>
                <th>NO</th>
                <th>Jam</th>
                <th>Nama Anggota</th>
                <th>Lokasi</th>
                <th>Finding</th>
                <th>Remarks</th>
                
            </tr>
        </thead>
        <tbody>
            @php 
                $no=1;
                $no2=0;
            @endphp
                @foreach($patroli as $row)
                    @php 
                        $img = '';
                        if (!empty($row->image)) {
                            $img = "<img src='https://hris.truest.co.id" . $row->image. "' style='width:50px;'>";
                        }
                        
                        $jam='';
                        if(!empty($row->jam_patrol)){
                            $jam=date('H:i:s', strtotime($row->jam_patrol));
                        }

                        $karyawan="";
                        if(!empty($row->employee_code)){
                            $karyawan=@karyawan_bynik($row->employee_code)->nama;
                        }
                    @endphp
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $jam }}</td>
                        <td>{{ $karyawan  }}</td>
                        <td>{{ $row->judul }}</td>
                        <td>{!! $img !!}</td> {{-- Use {!! !!} to render HTML from variable --}}
                        <td>{{ $row->description }}</td>
                    </tr>
                    @php 
                        // Increment $no and reset it if it reaches jml_patrol
                        $no++;
                    @endphp
                @endforeach
             
        </tbody>

    </table>
</body>
</html>