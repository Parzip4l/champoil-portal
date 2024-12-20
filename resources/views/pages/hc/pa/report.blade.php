<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Appraisal Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center;">Performance Appraisal Report</h1>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>NIK</th>
                <th>Periode</th>
                <th>Tahun</th>
                <th>Nilai Rata-Rata</th>
                <th>Predikat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($performanceData as $data)
                <tr>
                    <td>{{ $data['employee_name'] }}</td>
                    <td>{{ $data['nik'] }}</td>
                    <td>{{ $data['periode'] }}</td>
                    <td>{{ $data['tahun'] }}</td>
                    <td>{{ number_format($data['average_nilai'], 2) }}</td>
                    <td>{{ $data['predikat_name'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
