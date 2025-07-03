<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Illuminate\Support\Facades\Http;
use DateTime;

class OcrController extends Controller
{
    public function parseImage(Request $request)
    {
        if($request->type_ocr == 'kta'){
            $request->validate([
                'kta' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);
    
            // Simpan sementara file
            $imagePath = $request->file('kta')->store('temp');
        }else{
            $request->validate([
                'ktp' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            ]);
    
            // Simpan sementara file
            $imagePath = $request->file('ktp')->store('temp');
        }
        

        $ocrApiKey = 'K82558105388957';

        $response = Http::asMultipart()->post('https://api.ocr.space/parse/image', [
            [
                'name'     => 'apikey',
                'contents' => $ocrApiKey
            ],
            [
                'name'     => 'language',
                'contents' => 'eng' // atau 'ind' kalau pakai bahasa Indonesia
            ],
            [
                'name'     => 'isOverlayRequired',
                'contents' => 'false'
            ],
            [
                'name'     => 'OCREngine',
                'contents' => 2
            ],
            [
                'name'     => 'scale',
                'contents' => 'true'
            ],
            [
                'name'     => 'isTable',
                'contents' => 'true'
            ],
            [
                'name'     => 'file',
                'contents' => fopen(storage_path("app/{$imagePath}"), 'r'),
                'filename' => basename($imagePath)
            ]
        ]);

        $result = $response->json();
        $text = $result['ParsedResults'][0]['ParsedText'] ?? '';
        if($request->type_ocr == 'kta'){
            // $parsedData = $text;
            $parsedData = $this->parseRawKtaText($text);
        }else{
            $parsedData = $this->parseRawKtpText($text);
            // $parsedData = $text;
        }
        


        return response()->json([
            'success' => true,
            'filename' => basename($imagePath),
            'data' => $parsedData
        ]);
    }
    private function parseRawKtpText($text) {
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_filter(array_map('trim', $lines));
    
        $data = [
            'nik'             => null,
            'nama'            => null,
            'tempat_lahir'    => null,
            'tanggal_lahir'   => null,
            'jenis_kelamin'   => null,
            'golongan_darah'  => null,
            'alamat'          => null,
            'rt_rw'           => null,
            'kel_desa'        => null,
            'kecamatan'       => null,
            'agama'           => null,
            'status'          => null,
        ];
    
        $agamaMap = [
            'islam'     => 1,
            'kristen'   => 2,
            'katolik'   => 3,
            'hindu'     => 4,
            'buddha'    => 5,
            'budha'     => 5,
            'konghucu'  => 6,
        ];
    
        $statusMap = [
            'belum kawin' => 0,
            'kawin' => 1,
            'cerai hidup' => 2,
            'cerai mati' => 3,
        ];
    
        $golonganList = ['A', 'B', 'AB', 'O'];
    
        foreach ($lines as $line) {
            $line = preg_replace('/\s+/', ' ', $line); // Normalisasi spasi
    
            if (!$data['nik'] && preg_match('/nik[:\s]*([0-9]{16})/i', $line, $match)) {
                $data['nik'] = $match[1];
            }
    
            if (!$data['nama'] && preg_match('/nama[:\s]*([A-Z\s\'\-\.]+)/i', $line, $match)) {
                $data['nama'] = trim($match[1]);
            }
    
            if ((!$data['tempat_lahir'] || !$data['tanggal_lahir']) && preg_match('/tempat.*lahir[:\s]*([A-Z\s]+),\s*(\d{2}-\d{2}-\d{4})/i', $line, $match)) {
                $data['tempat_lahir'] = trim($match[1]);
                $data['tanggal_lahir'] = $match[2];
            }
    
            if (!$data['jenis_kelamin'] && preg_match('/jenis kelamin[:\s]*(laki-laki|perempuan)/i', $line, $match)) {
                $data['jenis_kelamin'] = ucfirst(strtolower($match[1]));
            }
    
            if (!$data['golongan_darah'] && preg_match('/gol\.? darah[:\s]*([A-Z]+)/i', $line, $match)) {
                $gol = strtoupper($match[1]);
                $data['golongan_darah'] = in_array($gol, $golonganList) ? $gol : 'L'; // 'L' untuk LAINNYA
            }
    
            if (!$data['alamat'] && preg_match('/alamat[:\s]*([A-Z0-9\s]+)/i', $line, $match)) {
                $data['alamat'] = trim($match[1]);
            }
    
            if (!$data['rt_rw'] && preg_match('/rt\/rw[:\s]*([0-9]{3}\/[0-9]{3})/i', $line, $match)) {
                $data['rt_rw'] = $match[1];
            }
    
            if (!$data['kel_desa'] && preg_match('/kel\/desa[:\s]*([A-Z\s]+)/i', $line, $match)) {
                $data['kel_desa'] = trim($match[1]);
            }
    
            if (!$data['kecamatan'] && preg_match('/kecamatan[:\s]*([A-Z\s]+)/i', $line, $match)) {
                $data['kecamatan'] = trim($match[1]);
            }
    
            if (!$data['agama'] && preg_match('/agama[:\s]*([a-z]+)/i', $line, $match)) {
                $agamaLower = strtolower($match[1]);
                $data['agama'] = $agamaMap[$agamaLower] ?? null;
            }
    
            if (!$data['status'] && preg_match('/status perkawinan[:\s]*([A-Z\s]+)/i', $line, $match)) {
                $status = strtolower(trim($match[1]));
                $data['status'] = $statusMap[$status] ?? null;
            }
        }
    
        if ($data['tanggal_lahir']) {
            $dt = DateTime::createFromFormat('d-m-Y', $data['tanggal_lahir']);
            if ($dt) {
                $data['tanggal_lahir'] = $dt->format('Y-m-d');
            }
        }
    
        return $data;
    }
    
    private function parseRawKtaText($text){
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $lines = array_values(array_filter(array_map('trim', $lines)));

        $data = [
            'nomor'         => null,
            'nama'          => null,
            'pekerjaan'     => null,
            'no_reg'        => null,
            'jabatan'       => null,
            'alamat'        => null,
            'berlaku_sd'    => null,
            'dikeluarkan_di'=> null,
            'pada_tanggal'  => null,
            'bb'            => null,
            'ditandatangani_oleh' => null,
            'nrp'           => null,
        ];

        foreach ($lines as $i => $line) {
            $cleanLine = preg_replace('/\s+/', ' ', trim($line));

            // Nomor
            if (!$data['nomor'] && stripos($cleanLine, 'kta') !== false && preg_match('/\d+\/KTA\s+SATPAM\/[A-Z]+\/\d{4}/i', $cleanLine)) {
                $data['nomor'] = $cleanLine;
            }

            // Nama
            if (!$data['nama'] && preg_match('/^Nama\s*(.+)$/i', $cleanLine, $match)) {
                $data['nama'] = trim($match[1]);
            }

            // Pekerjaan
            if (!$data['pekerjaan'] && preg_match('/^Pekerjaan\s*(.+)$/i', $cleanLine, $match)) {
                $data['pekerjaan'] = trim($match[1]);
            } elseif (!$data['pekerjaan'] && preg_match('/^PT\.\s?.+/i', $cleanLine)) {
                $data['pekerjaan'] = $cleanLine;
            }

            // No. Reg
            if (!$data['no_reg'] && preg_match('/No\.?\s*Reg\.?\s*[:\-]?\s*([A-Z0-9\.]+)/i', $cleanLine, $match)) {
                $data['no_reg'] = $match[1];
            } elseif (!$data['no_reg'] && preg_match('/\d{2}\.\d{2}\.\d{5,}/', $cleanLine, $match)) {
                $data['no_reg'] = $match[0];
            }

            // Jabatan
            if (!$data['jabatan'] && preg_match('/^Jabatan\s*(.+)$/i', $cleanLine, $match)) {
                $data['jabatan'] = trim($match[1]);
            } elseif (!$data['jabatan'] && preg_match('/^ANGGOTA$/i', $cleanLine)) {
                $data['jabatan'] = 'ANGGOTA';
            }

            // Alamat
            if (!$data['alamat'] && preg_match('/^Alamat\s*(.+)$/i', $cleanLine, $match)) {
                $data['alamat'] = trim($match[1]);
            } elseif (!$data['alamat'] && preg_match('/^JAKARTA.+$/i', $cleanLine)) {
                $data['alamat'] = $cleanLine;
            }

            // Berlaku s.d
            if (!$data['berlaku_sd'] && preg_match('/berlaku.*s\.d/i', $line)) {
                $combined = $line . ' ' . ($lines[$i + 1] ?? '');
            
                // Tangkap tanggal seperti "05 Des 2026" atau "D5 Des 2026"
                if (preg_match('/(D?\d{1,2})\s*Des\s*(\d{4})/i', $combined, $match)) {
                    // Perbaiki jika awalnya 'D5' (OCR error), ganti jadi '05'
                    $day = preg_replace('/^D/', '0', $match[1]);
                    $month = '12'; // Desember
                    $year = $match[2];
            
                    $dateString = sprintf('%s-%s-%s', $year, $month, str_pad($day, 2, '0', STR_PAD_LEFT));
                    $data['berlaku_sd'] = $dateString;
                }
            }
            

            // Dikeluarkan di
            if (!$data['dikeluarkan_di'] && stripos($cleanLine, 'dikeluarkan di') !== false) {
                $data['dikeluarkan_di'] = trim(str_replace('Dikeluarkan di', '', $cleanLine));
            }

            // Pada Tanggal
            if (!$data['pada_tanggal'] && stripos($cleanLine, 'pada tanggal') !== false && preg_match('/\d{2}\s+Des\s+\d{4}/i', $cleanLine, $match)) {
                $data['pada_tanggal'] = $match[0];
            }

            // BB
            if (!$data['bb'] && preg_match('/^BB\s+(\d+)/i', $cleanLine, $match)) {
                $data['bb'] = 'BB ' . $match[1];
            }

            // Ditandatangani oleh dan NRP
            if (!$data['ditandatangani_oleh'] && preg_match('/^KOMBES POL.*NRP\s+(\d+)/i', $cleanLine, $match)) {
                $data['ditandatangani_oleh'] = trim($cleanLine);
                $data['nrp'] = $match[1];
            }
        }

        return $data;
    }
}
