<?php

namespace App\Http\Controllers\Api\Patroli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PDF;
use Illuminate\Support\Facades\Auth;
use App\ModelCG\Lapsit;
use App\ModelCG\LapsitActivity;
use App\ModelCG\Project;
use Intervention\Image\Facades\Image;

class LeaderController extends Controller
{
    // List all records
    public function index($project="")
    {
        
        
        $records = Lapsit::where('project_id',$project)->where('category',1)->get();
        if ($records) {
            foreach ($records as $row) {
                $row->url_file = url('storage/images/' . $row->unix_code . '.png');
                $row->created_at = date('Y-m-d H:i:s', strtotime($row->created_at));
            }
        }
        return response()->json($records, 200);
    }

    // Store a new record
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'project_id' => 'required|integer',
                'judul' => 'required|string|max:255',
            ]);

            // Generate a random unique unix_code
            $unixCode = Str::random(10); // 10-character random string
            $validated['unix_code'] = $unixCode;
            $validated['created_at'] = now();
            $validated['category'] = 1; // Set category to 1 for Leader

            // Create a QR Code using the unix_code
            $qrCode = new QrCode($unixCode);
            $writer = new PngWriter();

            // Save the QR code as an image file
            $image = $writer->write($qrCode);

            // Define the file path in the public directory
            $filePath = public_path('qr_codes/qr_code_' . $unixCode . '.png');

            // Ensure the directory exists
            if (!file_exists(dirname($filePath))) {
                mkdir(dirname($filePath), 0755, true);
            }

            // Save the image to the public directory
            file_put_contents($filePath, $image->getString());

            // Optionally save the project in the database
            Lapsit::insert($validated);

            // Return a success response with the file path
            return response()->json([
                'message' => 'QR code generated and saved successfully',
                'qr_code_path' => asset('qr_codes/qr_code_' . $unixCode . '.png'), // URL to access the saved QR code
            ]);
        } catch (\Exception $e) {
            // Log the error message for debugging
            \Log::error('Error saving QR Code: ' . $e->getMessage());

            // Return an error response
            return response()->json([
                'message' => 'An error occurred while saving the QR Code.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show a specific record
    public function show($id)
    {
        $project = Lapsit::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project, 200);
    }

    // Update a specific record
    public function update(Request $request, $id)
    {
        $project = Lapsit::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $validated = $request->validate([
            'project_id' => 'required|integer',
            'judul' => 'required|string',
        ]);

        $project->update($validated);

        return response()->json(['message' => 'Project updated successfully', 'data' => $project], 200);
    }

    // Delete a specific record
    public function destroy($id)
    {
        $project = Lapsit::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }

    // Download QR code image
    public function download($unixCode)
    {
        // Retrieve the record from the database based on the Unix code
        $record = Lapsit::where('unix_code', $unixCode)->first();

        // Check if the record exists
        if (!$record) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        // Define the storage path for the QR code image
        $filePath = 'qr_codes/qr_code_' . $record->unix_code . '.png'; // Relative path in public/
        $imagePath = public_path($filePath); // Absolute path to the image

        // Check if the image exists
        if (!file_exists($imagePath)) {
            return response()->json(['message' => 'QR code not found'], 404);
        }

        // Define the HTML content for the PDF
        $pdfContent = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>QR Code</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    padding: 20px;
                    text-align: center;
                }
                .title {
                    font-size: 18px;
                    margin-bottom: 20px;
                }
                .qr-image img {
                    width: 150px;
                    height: auto;
                    margin: 0 auto;
                }
            </style>
        </head>
        <body>
            <div class='title'>
                {$record->judul}
            </div>
            <div class='qr-image'>
                <img src='{$imagePath}' alt='QR Code' />
            </div>
        </body>
        </html>
        ";

        // Generate the PDF from the inline HTML content using DomPDF
        $pdf = Pdf::loadHTML($pdfContent);

        // Set custom page size to 500px x 500px
        $pdf->setPaper([0, 0, 300, 300]); // [x1, y1, x2, y2] for 500x500 px

        // Return the PDF as a downloadable file
        return $pdf->download('qr_code_' . $unixCode . '.pdf');
    }

    public function project_lapsit($unixCode)
    {
        $url = route('activity-leader', ['id' => $unixCode]);
        $return = [
            "url" => $url
        ];
        return response()->json($return, 200);
    }

    public function storeActivity(Request $request)
    {
        $validated = $request->validate([
            'images' => 'required|image|mimes:jpg,jpeg,png',  // Adjust image validation rules as necessary
            'unix_code' => 'required|string',
            'employee' => 'required|string', // Validate employee_id as required
            'remarks' => 'nullable|string', // Remarks can be optional or empty
        ]);

        // Get the unix_code from the request
        $unixCode = $validated['unix_code'];

        // Retrieve the file from the request
        $image = $request->file('images'); // Make sure 'images' matches the name attribute of the file input

        // Check if an image was uploaded
        if (!$image) {
            return response()->json(['message' => 'No image uploaded'], 400);
        }

        // Find the corresponding Lapsit project using unix_code
        $lapsit = Lapsit::where('unix_code', $unixCode)->first();

        // Check if the Lapsit project was found
        if (!$lapsit) {
            return response()->json(['message' => 'Lapsit project not found'], 404);
        }

        // Define the S3 folder path and filename
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $path = 'truest-storage/storage/app/public/images/lapsit/lapsit-' . date('m-Y');

        // Upload the image to S3
        $url = uploadToS3($image, $path, $filename);

        // Create a new LapsitActivity record
        $lapsitActivity = LapsitActivity::create([
            'lapsit_id' => $lapsit->id,  // Assuming Lapsit project id is the foreign key
            'employee' => $validated['employee'], // Employee ID from the request
            'images' => $url, // S3 URL of the uploaded image
            'remarks' => $validated['remarks'] ?? null, // Optional remarks, if provided
        ]);

        // Respond with success
        return response()->json(['message' => 'Activity submitted successfully!', 'file_path' => $url], 200);
    }

    public function download_file_patrol(Request $request){
        try {
            ini_set('memory_limit', '4096M');
            set_time_limit(0);
            // Parse request inputs
            $tanggal = $request->input('tanggal');
            $project_id =  $request->input('project_id');
            $jam1 = $request->input('jam1');
            $jam2 = $request->input('jam2');

            $explode = explode(' to ', $tanggal);
            $jml_tgl = count($explode);

            if ($jml_tgl > 1) {
                $date1 = Carbon::parse($explode[0]);
                $date2 = Carbon::parse($explode[1]);
            } else {
                $date1 = Carbon::parse($explode[0]);
                $date2 = Carbon::parse($explode[0]);
            }

            $start = $date1->format('Y-m-d') . " $jam1";
            $end = $date2->format('Y-m-d') . " $jam2";

            // Fetch project details
            $project = Project::find($project_id);

            if (!$project) {
                return response()->json(['error' => 'Project not found'], 404);
            }

            // Fetch patrol records
            $records = Lapsit::select('lapsits.judul','lapsit_activities.*')
                ->join('lapsit_activities', 'lapsit_activities.lapsit_id', '=', 'lapsits.id')
                ->whereBetween('lapsit_activities.created_at', [$start, $end])
                ->where('lapsits.category',1)
                ->where('lapsits.project_id', $project_id)
                ->orderBy('lapsit_activities.created_at','asc')
                ->get();

            foreach ($records as $row) {
                $row->image = $row->images;
            }

           
            $data = [
                'patroli' => $records,
                'jam' => "$jam1 - $jam2",
                'filter' => "$date1 $jam1 - $date2 $jam2",
                'project' => $project->name ?? 'Unknown Project',
                'tanggal' => $tanggal ?? '',
                'title'=>"LAPSIT",
                'code'=>'lapsit'
            ];
            

            // Generate the PDF
            $pdf = Pdf::loadView('pages.operational.patroli_project.patrol_pdf_dt', $data);

            // Pastikan orientasi kertas diatur ke 'portrait'
            $pdf->setPaper('legal', 'portrait');

            // Tambahkan CSS untuk memastikan layout tetap potret
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('isRemoteEnabled', true); // Pastikan remote assets seperti gambar dapat diakses

            // Create unique file name for the PDF
            $fileName = 'report_' . date('Ymd') . ".pdf";
            $publicPath = public_path('reports');

            // Ensure the directory exists
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $fileName;

            // Save the PDF
            $pdf->save($filePath);

            $fileUrl = asset('reports/' . $fileName);

            // Return JSON response with file details
            return response()->json([
                'message' => 'PDF file generated successfully',
                'path' => $fileUrl,
                'file_name' => $fileName,
                'project' => $project->name
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'error' => 'Failed to generate PDF',
                'details' => $e->getMessage()
            ], 500);
        }
    }
}
