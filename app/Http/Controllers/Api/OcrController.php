<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrController extends Controller
{
    public function cek_ocr(Request $request){
        $result = [];

        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Store the uploaded file
        $filePath = $request->file('image')->store('ocr_images', 'public');

        // Preprocess the image to improve OCR accuracy
        $this->preprocessImage(storage_path('app/public/' . $filePath));

        try {
            // Ensure TESSDATA_PREFIX is set
            if (!env('TESSDATA_PREFIX')) {
                putenv('TESSDATA_PREFIX=' . base_path('tessdata')); // Set to your tessdata directory
            }

            // Perform OCR on the uploaded file
            $ocr = new TesseractOCR(storage_path('app/public/' . $filePath));
            $text = $ocr->lang('ind') // Attempt to use Indonesian language
                        ->psm(6) // Adjust Page Segmentation Mode for better results
                        ->run();

            // Log the raw OCR output for debugging
            \Log::info('Raw OCR Output: ' . $text);

            // Clean up the OCR result
            $cleanedText = preg_replace('/\s+/', ' ', trim($text));

            // Parse KTP data
            if ($request->input('document_type') == 'ktp') {
                $result = $this->parseKtpData($cleanedText);
            }else if ($request->input('document_type') == 'kta') {
                $result = $this->parseKtaData($cleanedText);
            }
        } catch (\Exception $e) {
            // Fallback to English if Indonesian language fails
            try {
                $ocr = new TesseractOCR(storage_path('app/public/' . $filePath));
                $text = $ocr->lang('eng') // Fallback to English
                            ->psm(6)
                            ->run();

                \Log::info('Fallback Raw OCR Output: ' . $text);

                $cleanedText = preg_replace('/\s+/', ' ', trim($text));

                if ($request->input('document_type') == 'ktp') {
                    $result = $this->parseKtpData($cleanedText);
                }else if ($request->input('document_type') == 'kta') {
                    $result = $this->parseKtaData($cleanedText);
                }
                


            } catch (\Exception $fallbackException) {
                // Handle OCR errors with detailed exception message for debugging
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to process the image. Ensure the image is clear and in a supported format.',
                    'details' => $fallbackException->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'error' => false,
            'result' => $result    
        ]);
    }

    private function preprocessImage($filePath) {
        // Use Intervention Image to preprocess the image
        $image = \Intervention\Image\Facades\Image::make($filePath);

        $image->greyscale();           // Convert to grayscale to remove colors
        $image->contrast(100);         // Aggressively increase contrast
        $image->brightness(-60);       // Darken the image to remove light background
        $image->blur(1);               // Apply slight blur to reduce noise
        $image->sharpen(0.5);           // Sharpen the text for better clarity
        $image->limitColors(2);        // Reduce colors to black and white (thresholding)

        // Resize to a higher resolution for better OCR accuracy
        $image->resize(3000, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        // Save the preprocessed image
        $image->save($filePath);
    }

    private function parseKtaData($text) {
        $data = [];

        // Log the cleaned OCR text for debugging
        \Log::info('Cleaned OCR Text: ' . $text);

        // Extract Nomor
        if (preg_match('/Nomor[:\s]*([A-Za-z0-9\/\.\s]+)/i', $text, $matches)) {
            $data['nomor'] = trim($matches[1]);
        }

        // Extract Nama
        if (preg_match('/Nama[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['nama'] = trim($matches[1]);
        }

        // Extract Pekerjaan
        if (preg_match('/Pekerjaan[:\s]*([A-Za-z\s\.]+)/i', $text, $matches)) {
            $data['pekerjaan'] = trim($matches[1]);
        }

        // Extract No. Reg.
        if (preg_match('/No\. Reg\.[:\s]*([\d\/\.]+)/i', $text, $matches)) {
            $data['no_reg'] = trim($matches[1]);
        }

        // Extract Jabatan
        if (preg_match('/Jabatan[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['jabatan'] = trim($matches[1]);
        }

        // Extract Alamat
        if (preg_match('/Alamat[:\s]*([A-Za-z0-9\s,\.\/]+)/i', $text, $matches)) {
            $data['alamat'] = trim($matches[1]);
        }

        // Extract Berlaku s.d and convert to ISO format
        if (preg_match('/Berlaku s\.d[:\s]*([0-9]{2}\s[A-Za-z]+\s[0-9]{4})/i', $text, $matches)) {
            $data['berlaku_sd'] = $this->convertDateToISO($matches[1]);
        }

        // Extract Dikeluarkan di
        if (preg_match('/Dikeluarkan di[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['dikeluarkan_di'] = trim($matches[1]);
        }

        // Extract Pada Tanggal and convert to ISO format
        if (preg_match('/Pada Tanggal[:\s]*([0-9]{2}\s[A-Za-z]+\s[0-9]{4})/i', $text, $matches)) {
            $data['pada_tanggal'] = $this->convertDateToISO($matches[1]);
        }

        return $data;
    }

    private function parseKtpData($text) {
        $data = [];

        // Log the cleaned OCR text for debugging
        \Log::info('Cleaned OCR Text: ' . $text);

        // Extract Nomor (handle multiline or extended formats)
        if (preg_match('/Nomor[:\s]*([A-Za-z0-9\/\.\s]+)(?=\sNama|$)/i', $text, $matches)) {
            $data['nomor'] = trim($matches[1]); // Capture until "Nama" or end of line
        } else {
            \Log::warning('Failed to extract Nomor from OCR text.');
        }

        // Extract Nama
        if (preg_match('/Nama[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['nama'] = trim($matches[1]);
        } else {
            \Log::warning('Failed to extract Nama from OCR text.');
        }

        // Extract Tempat Lahir and Tanggal Lahir
        if (preg_match('/Tempat\/Tgl Lahir[:\s]*([A-Za-z\s]+),\s*([0-9]{2}\s[A-Za-z]+\s[0-9]{4})/i', $text, $matches)) {
            $data['tempat_lahir'] = trim($matches[1]);
            $data['tanggal_lahir'] = $this->convertDateToISO($matches[2]);
        }

        // Extract Jenis Kelamin
        if (preg_match('/Jenis Kelamin[:\s]*([A-Za-z]+)/i', $text, $matches)) {
            $data['jenis_kelamin'] = trim($matches[1]);
        }

        // Extract Alamat
        if (preg_match('/Alamat[:\s]*([A-Za-z0-9\s,\.\/]+)/i', $text, $matches)) {
            $data['alamat'] = trim($matches[1]);
        }

        // Extract RT and RW
        if (preg_match('/RT\/RW[:\s]*([0-9]+)\/([0-9]+)/i', $text, $matches)) {
            $data['rt'] = $matches[1];
            $data['rw'] = $matches[2];
        }

        // Extract Kelurahan and Kecamatan
        if (preg_match('/Kel\/Desa[:\s]*([A-Za-z\s]+)\s*Kecamatan[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['kelurahan'] = trim($matches[1]);
            $data['kecamatan'] = trim($matches[2]);
        }

        // Extract Agama
        if (preg_match('/Agama[:\s]*([A-Za-z]+)/i', $text, $matches)) {
            $data['agama'] = trim($matches[1]);
        }

        // Extract Status Perkawinan
        if (preg_match('/Status Perkawinan[:\s]*([A-Za-z]+)/i', $text, $matches)) {
            $data['status_perkawinan'] = trim($matches[1]);
        }

        // Extract Pekerjaan
        if (preg_match('/Pekerjaan[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['pekerjaan'] = trim($matches[1]);
        }

        // Extract Berlaku s.d and convert to ISO format
        if (preg_match('/Berlaku s\.d[:\s]*([0-9]{2}\s[A-Za-z]+\s[0-9]{4})/i', $text, $matches)) {
            $data['berlaku_sd'] = trim($matches[1]);
        }

        // Extract Dikeluarkan di
        if (preg_match('/Dikeluarkan di[:\s]*([A-Za-z\s]+)/i', $text, $matches)) {
            $data['dikeluarkan_di'] = trim($matches[1]);
        }

        // Extract Pada Tanggal and convert to ISO format
        if (preg_match('/Pada Tanggal[:\s]*([0-9]{2}\s[A-Za-z]+\s[0-9]{4})/i', $text, $matches)) {
            $data['pada_tanggal'] = $this->convertDateToISO($matches[1]);
        }

        return $data;
    }

    private function convertDateToISO($dateString) {
        // Convert date from "05 Des 2026" to "2026-12-05"
        $months = [
            'Jan' => '01', 'Feb' => '02', 'Mar' => '03', 'Apr' => '04',
            'Mei' => '05', 'Jun' => '06', 'Jul' => '07', 'Agu' => '08',
            'Sep' => '09', 'Okt' => '10', 'Nov' => '11', 'Des' => '12'
        ];

        if (preg_match('/([0-9]{2})\s([A-Za-z]+)\s([0-9]{4})/', $dateString, $matches)) {
            $day = $matches[1];
            $month = $months[$matches[2]] ?? null;
            $year = $matches[3];

            if ($month) {
                return "$year-$month-$day";
            }
        }

        return null; // Return null if the date format is invalid
    }
}
