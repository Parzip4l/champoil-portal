<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Perintah Tugas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px; /* Reduced font size */
            line-height: 1.6;
            background-image: url('{{ public_path('images/surat_tugas.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin: 20px;
        }
        .footer {
            margin-top: 30px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 10px;
        }
        .ttd {
            text-align: center;
        }
        .ttd2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="width: 20%;">
                    <img src="{{ public_path('images/cityservice.png') }}" style="width:120px;" alt="Company Logo">
                </td>
                <td style="margin-left:350px; width: 60%;">
                <h1 style="margin-top:25px">SURAT PERINTAH TUGAS</h1>
                </td>
            </tr>
        </table>
        <p style="text-align:center"><b>NOMOR: {{ $nomor }}/{{ $kode }}/{{ $divisi }}/{{ $bulan }}/{{ $tahun }}</b></p><hr/>
    </div>
    <div class="content">
        <p class="section-title">PERTIMBANGAN:</p>
        <p>Bahwa untuk kepentingan dinas dan kelancaran tugas pengamanan CITY SERVICE di area <b><i>{{ $project }}</i></b> Maka dipandang perlu dikeluarkannya Surat Perintah Tugas ini.</p>

        <p class="section-title">DASAR:</p>
        <ol>
            <li>UU No. 2 Tahun 2002 Tentang Kepolisian Negara Republik Indonesia</li>
            <li>PERPOL No. 4 Tahun 2020 Tentang Pengamanan Swakarsa</li>
        </ol>

        <p class="section-title">KEPADA:</p>
        <p>Nama: {{ $nama }}</p>
        <p>No. Reg: {{ $nik }}</p>
        <p>Jabatan: ANGGOTA</p>
        <p>TMT: {{ date('l, d F Y',strtotime($tmt)) }}</p>
        <p>Keterangan: {{ $keterangan }}</p>

        <p class="section-title">INSTRUKSI:</p>
        <ol>
            <li>Untuk melaksanakan TUGAS sebagai ANGGOTA</li>
            <li>Pelaksanaan tugas sesuai dengan jadwal yang berlaku.</li>
            <li>Dengan menggunakan Perlengkapan Dinas sesuai ketentuan.</li>
            <li>Agar melaksanakan tugas/perintah dengan penuh tanggung jawab</li>
            <li>Surat perintah ini berlaku sejak tanggal dikeluarkan sampai ada ketentuan lebih lanjut.</li>
        </ol>
    </div>
    <div class="footer">
        <p>Demikian Surat Perintah Tugas ini diterbitkan oleh CITY SERVICE pada tanggal <b>{{ date('l, d F Y',strtotime($tmt)) }}</b>, serta berlaku sebagai surat jalan dan untuk digunakan sebagaimana mestinya.</p>
        <p class="ttd2">Hormat Kami,</p>
        <div style="text-align: center;">
            <img src="{{ public_path('images/tt_wahyu.png') }}" style="width:90px;" alt="Company Logo">
        </div>
        <p class="ttd"><u>{{ $pembuat }}</u></p>
        <p class="ttd">Divisi Operational</p>
    </div>
</body>
</html>
