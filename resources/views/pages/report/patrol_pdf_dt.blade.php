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
    <h2 style="text-align:center">{{ $project }}</h2>
    <h4 style="text-align:center">{{ $filter }}</h4>
    <table>
        <thead>
            <tr>
                <th>SHIFT</th>
                <th>Date</th>
                <th>Time</th>
                <th>CODE</th>
                <th>LOCATIONS</th>
                <th>FINDINGS</th>
                <th>REMARKS</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no=1;
                $no2=0;
            @endphp
            @foreach($schedule as $key=>$val)
                @foreach($tanggal as $row)
                    @if(!empty($row->data_history))
                        @php 
                            $img = '';
                            if (!empty($row->data_history->image)) {
                                $img = "<img src='" . asset($row->data_history->image) . "' style='width:200px;'>";
                            }

                        @endphp
                        <tr>
                            <td>{{ $key }}</td>
                            <td>{{ date('d-m-Y', strtotime($row->tanggal_filter)) }}</td>
                            <td>{{ date('H:i:s', strtotime($row->data_history->created_at)) }}</td>
                            <td>{{ $row->unix_code }}</td>
                            <td>{{ $row->judul }}</td>
                            <td>{!! $img !!}</td> {{-- Use {!! !!} to render HTML from variable --}}
                            <td>{{ $row->data_history->description }}</td>
                        </tr>
                    @else
                        <tr style="background-color:#ff6f74;color:white">
                            <td>{{ $key }}</td>
                            <td>{{ date('d-m-Y', strtotime($row->tanggal_filter)) }}</td>
                            <td></td>
                            <td>{{ $row->unix_code }}</td>
                            <td>{{ $row->judul }}</td>
                            <td>{{ $row->created_at }}</td>
                            <td></td>
                        </tr>
                    @endif
                    @php 
                        $no++;
                    @endphp
                @endforeach
                @php 
                    $no2++;
                @endphp
            @endforeach
        </tbody>

    </table>
</body>
</html>