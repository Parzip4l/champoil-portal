<!DOCTYPE html>
<html>
<head>
    <title>Perjanjian Kerjasama Pinjaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            margin: 0 20px;
        }
        h3 {
            text-align: left;
            text-transform: uppercase;
        }

        h4 {
            margin-bottom : 5px!important;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
            padding: 5px;
        }

        ol {
            padding-left: 20px;
        }

        .signature {
            margin-top: 20px;
        }
        .signature table td {
            text-align: center;
            padding: 30px 0;
        }
        .padding-jorok {
            padding-left: 25px;
        }

        .margin-custom {
            margin-bottom : 0!important;
        }
    </style>
</head>
<body>
    <h3>Perjanjian Kerjasama Pinjaman</h3>
    <p>Pada hari ini, {{ now()->translatedFormat('l, d/m/Y') }}, bertempat di Gedung ESCA, Jl. Pluit Sakti No.36, RT.009/RW.007, Pluit, Kec. Penjaringan, Jakarta Utara, Daerah Khusus Ibukota Jakarta, kami yang bertanda tangan di bawah ini:</p>
    <div class="perjanjian-wrap padding-jorok">
        <ol class="padding-jorok">
            <li>
                <strong>KOPERASI TRUEST FUND BY KHARISMA ADHI SEJAHTERA, PT</strong>, berkedudukan di Gedung ESCA, Jl. Pluit Sakti No.36, RT.009/RW.007, Pluit, Kec. Penjaringan, Jakarta Utara, Daerah Khusus Ibukota Jakarta, yang selanjutnya dalam perjanjian ini disebut sebagai "PIHAK PERTAMA."
            </li>
            <li>
                <strong>{{ $peminjam->nama }}</strong>, berkedudukan di <strong>{{ $peminjam->alamat }}</strong>, yang selanjutnya dalam perjanjian ini disebut sebagai "PIHAK KEDUA."
            </li>
        </ol>
    </div>
    <p>Secara bersama-sama disebut sebagai "PARA PIHAK."</p>

    <p>Dengan ini PARA PIHAK sepakat untuk mengadakan Perjanjian Kerjasama Pinjaman dengan ketentuan dan syarat sebagai berikut:</p>

    <h4>PASAL 1: OBJEK PERJANJIAN</h4>
    <p>PIHAK PERTAMA setuju untuk memberikan pinjaman uang kepada PIHAK KEDUA dengan rincian sebagai berikut:</p>
    <table>
        <tr>
            <td>Nominal Pinjaman</td>
            <td>: Rp {{ number_format($nominalPinjaman, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Pembelian Merchandise</td>
            <td>: Rp {{ number_format($merchandise, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>TRUEST App Membership</td>
            <td>: Rp {{ number_format($membership, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Pinjaman</td>
            <td>: Rp {{ number_format($recomputedTotal, 0, ',', '.') }}</td>
        </tr>
    </table>

    <h4>PASAL 2: JANGKA WAKTU PINJAMAN</h4>
    <p class="padding-jorok">1. Jangka waktu pinjaman adalah 03 bulan, terhitung sejak tanggal {{ now()->translatedFormat('d/m/Y') }}.</p>
    <p class="padding-jorok">2. Peminjaman tidak dapat diperpanjang hingga pinjaman dilunasi secara keseluruhan.</p>

    <h4>PASAL 3: BIAYA ADMINISTRASI</h4>
    <p class="padding-jorok">1. PIHAK KEDUA berkewajiban untuk membeli bundling merchandise sesuai yang tertuang pada PASAL 1.</p>
    <p class="padding-jorok">2. PIHAK KEDUA juga akan dikenakan biaya TRUEST App Membership sesuai yang tertuang pada PASAL 1.</p>
    <br>

    <h4>PASAL 4: CARA PEMBAYARAN</h4>
    <p class="padding-jorok">1. PIHAK KEDUA wajib melakukan pembayaran pinjaman sesuai dengan jadwal pembayaran yang telah ditetapkan, yaitu pada akhir tanggal setiap bulan selama periode perjanjian serta dipotong melalui pembayaran gaji.</p>
    <p class="padding-jorok">2. Untuk satu dan lain hal pembayaran pinjaman dapat ditransfer ke rekening:</p>
    <p class="padding-jorok">BCA<br>5380333335<br>KHARISMA ADHI SEJAHTERA PT</p>

    <h4>PASAL 5: SANKSI ATAS KETERLAMBATAN PEMBAYARAN</h4>
    <p class="padding-jorok">1. Apabila PIHAK KEDUA terlambat melakukan pembayaran, maka PIHAK KEDUA akan dikenakan denda keterlambatan sebesar 1% per hari dari jumlah pinjaman yang belum dibayar.</p>
    <p class="padding-jorok">2. Apabila keterlambatan melebihi 90 hari, PIHAK PERTAMA berhak untuk menempuh jalur hukum sesuai dengan peraturan perundang-undangan yang berlaku di Indonesia.</p>

    <h4>PASAL 6: HAK DAN KEWAJIBAN PARA PIHAK</h4>
    <p class="padding-jorok margin-custom"><strong>1. Hak PIHAK PERTAMA:</strong></p>
    <div class="perjanjian-wrap padding-jorok">
        <ul>
            <li>Menerima pembayaran penuh atas pinjaman dari PIHAK KEDUA sesuai jadwal dan tata cara pembayaran yang telah disepakati.</li>
            <li>Menerima hasil penjualan merchandise dan atau TRUEST App Membership dan atau denda sesuai dengan ketentuan perjanjian ini.</li>
        </ul>
    </div>
    
    <p class="padding-jorok margin-custom"><strong>2. Kewajiban PIHAK PERTAMA:</strong></p>
    <div class="perjanjian-wrap padding-jorok">
        <ul>
            <li>Mencairkan pinjaman sesuai dengan nominal yang disepakati ke rekening PIHAK KEDUA setelah perjanjian ini ditandatangani oleh PARA PIHAK.</li>
        </ul>
    </diV>
    
    <p class="padding-jorok margin-custom"><strong>3. Hak PIHAK KEDUA:</strong></p>
    <div class="perjanjian-wrap padding-jorok">
        <ul>
            <li>Menerima pencairan dana pinjaman sesuai dengan perjanjian ini.</li>
        </ul>
    </diV>
    <p class="padding-jorok margin-custom"><strong>4. Kewajiban PIHAK KEDUA:</strong></p>
    <div class="perjanjian-wrap padding-jorok">
        <ul>
            <li>Melakukan pembayaran atas pokok pinjaman, pembelian merchandise, dan TRUEST App Membership sesuai dengan ketentuan perjanjian ini.</li>
        </ul>
    </div>
    <br>
    <br>
    <br>
    <h4>PASAL 7: PENYELESAIAN SENGKETA</h4>
    <p class="padding-jorok">1. Apabila terjadi sengketa dalam pelaksanaan perjanjian ini, PARA PIHAK akan menyelesaikannya secara musyawarah untuk mufakat.</p>
    <p class="padding-jorok">2. Jika musyawarah tidak mencapai kesepakatan, sengketa akan diselesaikan melalui hukum yang berlaku di Indonesia.</p>

    <h4>PASAL 8: PENUTUP</h4>
    <p class="padding-jorok">Perjanjian ini dibuat dalam dua rangkap, masing-masing memiliki kekuatan hukum yang sama, dan berlaku sejak tanggal ditandatanganinya perjanjian ini oleh PARA PIHAK.</p>

    <div class="signature">
    <table>
    <tr>
        <td style="text-align: left; margin: 0;">PIHAK PERTAMA</td>
        <td style="width: 50px; margin: 0;" ></td> <!-- Spacer -->
        <td style="text-align: left; margin: 0;">PIHAK KEDUA</td>
    </tr>
    <tr>
        <td style="text-align: left; vertical-align: top;">
            <img src="https://hris.truest.co.id/images/approve.png" alt="Approved" style="max-width: 125px; display: block; margin-bottom: 5px;">
        </td>
        <td></td>
        <td style="text-align: left; vertical-align: top;">
            <img src="https://hris.truest.co.id/images/approve.png" alt="Approved" style="max-width: 125px; display: block; margin-bottom: 5px;">
            <p style="font-size: 10px; margin: 0;">{{ $peminjam->nik }}</p>
        </td>
    </tr>
    <tr>
        <td style="text-align: left; padding-top: 0;">
            <strong>DEDI SUPARTO</strong><br>
            <span style="font-size: 12px;">KOPERASI TRUEST FUND</span><br>
            <span style="font-size: 12px;">BY KHARISMA ADHI SEJAHTERA, PT</span>
        </td>
        <td></td>
        <td style="text-align: left; padding-top: 0;">
            <strong>{{ $peminjam->nama }}</strong>
        </td>
    </tr>
</table>

    </div>

</body>
</html>
