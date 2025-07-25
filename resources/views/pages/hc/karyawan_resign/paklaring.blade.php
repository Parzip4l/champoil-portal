<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAKLARING - {{ $employee['nama'] }}</title>
</head>
<body>
    <!-- Header with logo and title in one row using inline CSS -->
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div style="text-align: center;">
            <!-- Header or additional title can go here -->
        </div>
    </div>
    
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td>
                <img src="https://hris.truest.co.id/images/company_logo/cityservice.png" style="width: 110px;" alt="Company Logo">
            </td>
            <td style="text-align: center;">
                <!-- Placeholder for centered text if needed -->
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; margin-bottom: 40px; text-align: center;font-size:20px;">
        <thead>
            <tr>
                <td><strong><u>SURAT KETERANGAN KERJA MITRA</u></strong></td>
            </tr>
            <tr>
                <td><strong>CERTIFICATE OF EMPLOYMENT</strong></td>
            </tr>
            <tr>
                <td><em>{{$nomor}}/HC/SKK-KAS/{{$bulan}}/{{ $tahun }}</em></td>
            </tr>
        </thead>
    </table>
    
    <table style="width: 100%; margin-bottom:;font-size:17px;">
        <tr>
            <td colspan="3">
                Dengan ini menyatakan bahwa:<br>
                This is to certify that:
            </td>
        </tr>
        <tr>
            <td>Nama<br/>Name</td>
            <td style="width:3px">:</td>
            <td style="text-align:left">{{$employee->nama}}</td>
        </tr>
        <tr>
            <td>NIK<br/>Badge No.</td>
            <td>:</td>
            <td>{{$employee->nik}}</td>
        </tr>
        <tr>
            <td>Bagian<br/>Dept / Div</td>
            <td>:</td>
            <td>Operasional</td>
        </tr>
        <tr>
            <td>Jabatan<br/>Occupation</td>
            <td>:</td>
            <td>Security</td>
        </tr>
        <tr>
            <td>Lama Bekerja<br/>Period of Service</td>
            <td>:</td>
            <td>{{date('d F Y',strtotime($employee->joindate))}} to {{date('d F Y',strtotime($resign->created_at))}}</td>
        </tr>
        <tr>
            <td>Tempat Bekerja<br/>Working Location</td>
            <td>:</td>
            <td>PT Kharisma Adhi Sejahtera</td>
        </tr>
    </table>
    <hr>
    <p>
        Kami mengucapkan banyak terima kasih atas jasa dan kerjasama yang saudara/i berikan ke PT Kharisma Adhi Sejahtera, 
        dan semoga keberhasilan senantiasa menyertai Anda di masa yang akan datang.<br><br>
        We would like to take this opportunity to thank you for your sincere efforts and contribution to PT Kharisma Adhi Sejahtera, 
        and we wish you every success in the future.
    </p>
    <table style="width: 100%; margin-bottom: 10px; text-align: left; font-size: 20px;">
        <tr>
            <td>
                Jakarta, {{ date('d F Y',strtotime($resign->created_at)) }}<br/>
                Hormat Saya<br/>
                <br/>
                <img src="{{ public_path('images/ttd_rizka.png') }}" style="width: 90px; text-align: left;" alt="ttd">
                <img src="https://hris.truest.co.id/images/company_logo/stampel.png" style="width: 110px;margin-top:20px;margin-left:-60px;" alt="Company Logo"><br/>
                <u><strong>Rizka Mega Anggraeni</strong></u><br/>
                <p>Human Culture </p>

            </td>
        </tr>
    </table>
    <table style="width: 100%; margin-bottom: 10px; text-align: left; font-size: 15px;">
        <tr>
            <td>
                <img src="https://hris.truest.co.id/images/company_logo/cityservice.png" style="width: 100px;" alt="Company Logo">
            </td>
            <td style="text-align:center">
            <small>PT KHARISMA ADHI SEJAHTERA<br/>
            info@cityguard.co.id</small>

            </td>
            <td>
            <small>021-5404964<br/> Jakarta, 14460<br/>Indonesia</small>

            </td>
        </tr>
    </table>

</body>
</html>
