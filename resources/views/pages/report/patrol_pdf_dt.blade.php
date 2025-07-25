<!DOCTYPE html>
<html>
<head>
    <title>Patrol Report</title>
    <style>
        /* ...existing styles... */
        @page {
            margin: 0px;
        }
        body {
            margin: 160px 20px 180px 20px; /* Add margins to avoid overlap with header and footer */
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 150px; /* Define height for the header */
            margin: 0;
            padding: 0;
            text-align: center;
            background-image: url('{{ public_path('images/header.png') }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top center;
        }
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 130px; /* Define height for the footer */
            margin: 0;
            padding: 0;
            text-align: center;
            background-image: url('{{ public_path('images/footer.png') }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
</head>
<body>
    <header>
    <table style="padding-bottom:40px;width:75%">
        @if($project_id == 582307)
            <tr>
                <td>
                    <img src="https://hris.truest.co.id/images/company_logo/hilt2.png" style="width:120px;" alt="Company Logo">
                </td>
                <td style="text-align:center">
                    <h2 style="margin: 0;">DOUBLETREE By HILTON <br/>JAKARTA
                    KEMAYORAN</h2>
                    <h4 style="margin: 0;">{{ $filter }}</h4>
                </td>
            </tr>
        @else
            <tr>
                <td>
                    <img src="https://hris.truest.co.id/images/company_logo/hilt2.png" style="width:120px;" alt="Company Logo">
                </td>
                <td style="text-align:center">
                    <h2 style="margin: 0;">{{ $project }}</h2>
                    <h4 style="margin: 0;">{{ $filter }}</h4>
                </td>
            </tr>
        @endif
    </table>
    </header>
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
                $no2 = 0;
            @endphp
            @foreach($patroli as $row)
                @php 
                    $img = '';
                    if (!empty($row->image) && $row->status == 0) {
                        $img = "<img src='https://hris.truest.co.id" . $row->image. "' style='width:30px;'>";
                    }
                    
                    $jam = '';
                    if(!empty($row->jam_patrol)){
                        $jam = date('H:i:s', strtotime($row->jam_patrol));
                    }

                    $tanggal = '';
                    if(!empty($row->jam_patrol)){
                        $tanggal = date('Y-m-d', strtotime($row->jam_patrol));
                    }

                    $karyawan = "";
                    if(!empty($row->employee_code)){
                        $karyawan = @karyawan_bynik($row->employee_code)->nama;
                    }
                @endphp
                @if(!empty($tanggal) && $tanggal !='1970-01-02')
                <tr style="page-break-inside: avoid;">
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $no }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $tanggal }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $jam }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $karyawan }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $row->judul }}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{!! $img !!}</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $row->description }}</td>
                </tr>
                @endif
                @php 
                    $no++;
                @endphp
            @endforeach
        </tbody>
    </table>
    <footer>
        <!-- <p>Page <span class="page-number"></span></p> -->
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const pageNumbers = document.querySelectorAll('.page-number');
            pageNumbers.forEach((el, index) => {
                el.textContent = index + 1;
            });
        });
    </script>
</body>
</html>
