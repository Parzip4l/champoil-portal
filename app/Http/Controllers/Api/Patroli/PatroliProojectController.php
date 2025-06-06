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
use Intervention\Image\Facades\Image;

use App\ModelCG\PatrliProject as  PatroliProject;
use App\ModelCG\PatroliProjectAct;
use App\ModelCG\Project;

class PatroliProojectController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('project_id') ?? '';

        $query = PatroliProject::query();

        if (!empty($filter)) {
            $query->where('project_id', $filter);
        }

        $records = $query->get(); // ambil data-nya di sini

        if ($records->isNotEmpty()) {
            foreach ($records as $row) {
                $row->url_file = url('storage/images/' . $row->unix_code . '.png');
                $row->created_at = date('Y-m-d H:i:s', strtotime($row->created_at));
            }
        }

        return response()->json($records, 200);
    }

    // Store a new record
    public function store(Request $request){
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
            PatroliProject::insert($validated);

            // Return a success response with the file path
            return response()->json([
                'message' => 'QR code generated and saved successfully',
                'qr_code_path' => asset('qr_codes/qr_code_' . $unixCode . '.png'), // URL to access the saved QR code
            ]);
        } catch (\Exception $e) {
            // Log the error message for debugging
            Log::error('Error saving QR Code: ' . $e->getMessage());

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
        $project = PatroliProject::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project, 200);
    }

    // Update a specific record
    public function update(Request $request, $id)
    {
        $project = PatroliProject::find($id);

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
        $project = PatroliProject::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();

        return response()->json(['message' => 'Project deleted successfully'], 200);
    }

    // Download QR code image
    public function download($unixCode){
       
        // Retrieve the record from the database based on the Unix code
        $record = PatroliProject::where('unix_code', $unixCode)->first();

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

    public function project_patroli($unixCode){
        $url = route('activity-patroli',['id'=>$unixCode]);
        $return=[
            "url"=>$url
        ];
        return response()->json($return, 200);
    }

    public function storeActivity(Request $request){
        // Validate the incoming request
        // Validate the incoming request
        $validated = $request->validate([
            'images' => 'required|image|mimes:jpg,jpeg,png', // Adjust image validation rules as necessary
            'unix_code' => 'required|string',
            'employee_id' => 'required|string', // Validate employee_id as required
            'remarks' => 'required|string', // Remarks can be optional or empty
        ]);

        // Get the unix_code from the request
        $unixCode = $validated['unix_code'];

        // Retrieve the file from the request
        $image = $request->file('images'); // Make sure 'images' matches the name attribute of the file input

        // Check if an image was uploaded
        if (!$image) {
            return response()->json(['message' => 'No image uploaded'], 400);
        }

        // Find the corresponding PatroliProject using unix_code
        $patroli = PatroliProject::where('unix_code', $unixCode)->first();

        // Check if the PatroliProject was found
        if (!$patroli) {
            return response()->json(['message' => 'Patroli project not found'], 404);
        }

        // Resize the image using Intervention Image
        $resizedImage = Image::make($image->getRealPath())
            ->resize(1024, 768, function ($constraint) {
                $constraint->aspectRatio(); // Maintain aspect ratio
                $constraint->upsize();     // Prevent upsizing smaller images
            })
            ->encode('jpg', 75); // Encode as JPEG with 75% quality

        // Generate a unique file name
        $fileName = uniqid('patroli_') . '.jpg';

        // Save the resized image to the 'public' disk
        $filePath = 'project_patroli/' . $fileName;
        Storage::disk('public')->put($filePath, $resizedImage);

        // Create a new PatroliProjectAct record
        $patroliProjectAct = PatroliProjectAct::create([
            'patroli_atc_id' => $patroli->id, // Assuming PatroliProject's id is the foreign key
            'employee_id' => $validated['employee_id'], // Employee ID from the request
            'images' => $filePath, // Path to the resized image
            'remarks' => $validated['remarks'] ?? null, // Optional remarks, if provided
        ]);

        // Respond with success
        return response()->json(['message' => 'Activity submitted successfully!', 'file_path' => $filePath], 200);
    }

    public function download_file_patrol(Request $request){
        try {
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
            $records = PatroliProject::select('patrli_projects.judul','patroli_project_acts.*')
                ->join('patroli_project_acts', 'patroli_project_acts.patroli_atc_id', '=', 'patrli_projects.id')
                ->whereBetween('patroli_project_acts.created_at', [$start, $end])
                ->orderBy('patroli_project_acts.created_at','asc')
                ->get();

           
            $data = [
                'patroli' => $records,
                'jam' => "$jam1 - $jam2",
                'filter' => "$date1 $jam1 - $date2 $jam2",
                'project' => $project->name ?? 'Unknown Project',
                'tanggal' => $tanggal ?? '',
                'title'=>"PATROLI PROJECT",
                'code'=>'project'
            ];
            ini_set('memory_limit', '4096M');
            set_time_limit(0);
            // Generate the PDF
            if($project_id==582307){
                $pdf = Pdf::loadView('pages.operational.patroli_project.patrol_pdf_dt', $data);
            }else{
                $pdf = Pdf::loadView('pages.operational.patroli_project.global', $data);
            }
            
            $pdf->setOption('no-outline', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setPaper('legal', 'portrait');

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
