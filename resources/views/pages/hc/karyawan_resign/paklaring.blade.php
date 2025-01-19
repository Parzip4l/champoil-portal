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
                <img src="https://hris.truest.co.id/images/company_logo/cityservice.png" style="width: 200px;" alt="Company Logo">
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
                <td><em>001/HC/SKK-KAS/XII/2024</em></td>
            </tr>
        </thead>
    </table>
    
    <table style="width: 100%; margin-bottom: 40px;font-size:17px;">
        <tr>
            <td colspan="3">
                Dengan ini menyatakan bahwa:<br>
                This is to certify that:
            </td>
        </tr>
        <tr>
            <td>Nama<br/>Name</td>
            <td style="width:3px">:</td>
            <td style="text-align:left">Agung Wahyudi</td>
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
    <p>
        Kami mengucapkan banyak terima kasih atas jasa dan kerjasama yang saudara/i berikan ke PT Kharisma Adhi Sejahtera, 
        dan semoga keberhasilan senantiasa menyertai Anda di masa yang akan datang.<br><br>
        We would like to take this opportunity to thank you for your sincere efforts and contribution to PT Kharisma Adhi Sejahtera, 
        and we wish you every success in the future.
    </p>
    <table style="width: 100%; margin-bottom: 10px; text-align: left; font-size: 20px;">
    <tr>
        <td style="border: 1px solid black;">
            Jakarta, {{ date('d F Y') }}<br/>
            Hormat Saya<br/>
            <img src="https://hris.truest.co.id/images/company_logo/ttd.png" style="width: 100px; text-align: left;" alt="Company Logo">
            <img src="https://hris.truest.co.id/images/company_logo/stampel.png" style="width: 200px;margin-top:20px;margin-left:-60px;" alt="Company Logo"><br/>
            <u><strong>Moch. Firly Triyodha Kusuma, SE</strong></u><br/>
            <p>Human Culture Manager </p>

        </td>
    </tr>
</table>

</body>
</html>
