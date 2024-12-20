<?php

namespace App\Http\Controllers\Api\Patroli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PDF;

use App\ModelCG\PatrliProject as  PatroliProject;
use App\ModelCG\PatroliProjectAct;

class PatroliProojectController extends Controller
{
    public function index()
    {
        $records = PatroliProject::all();
        if($records){
            foreach($records as $row){
                $row->url_file = url('storage/images/' . $row->unix_code . '.png');
                $row->created_at =date('Y-m-d H:i:s',strtotime($row->created_at));
            }
        }
        return response()->json($records, 200);
    }

    // Store a new record
    public function store(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'project_id' => 'required|integer',
        'judul' => 'required|string|max:255',
    ]);

    // Generate a random unique unix_code
    $unixCode = Str::random(10); // 10-character random string
    $validated['unix_code'] = $unixCode;
    $validated['created_at'] = date('Y-m-d H:i:s');

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
    $project = PatroliProject::insert($validated);

    // Return a success response with the file path
    return response()->json([
        'message' => 'QR code generated and saved successfully',
        'qr_code_path' => asset('qr_codes/qr_code_' . $unixCode . '.png'), // URL to access the saved QR code
    ]);
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

    public function storeActivity(Request $request)
{
    // Validate the incoming request
    $validated = $request->validate([
        'images' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',  // Adjust image validation rules as necessary
        'unix_code' => 'required|string',
        'employee_id' => 'required|string', // Validate employee_id as required
        'remarks' => 'nullable|string', // Remarks can be optional or empty
    ]);

    // Get the unix_code from the request
    $unixCode = $validated['unix_code'];

    // Retrieve the file from the request
    $image = $request->file('images'); // Make sure 'image' matches the name attribute of the file input

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

    // Store the image in the 'public' disk (storage/app/public)
    $filePath = $image->store('project_patroli', 'public');  // Store the file in 'storage/app/public/project_patroli'

    // Create a new PatroliProjectAct record
    $patroliProjectAct = PatroliProjectAct::create([
        'patroli_atc_id' => $patroli->id,  // Assuming PatroliProject's id is the foreign key
        'employee_id' => $validated['employee_id'], // Employee ID from the request
        'images' => $filePath, // Path to the uploaded image
        'remarks' => $validated['remarks'] ?? null, // Optional remarks, if provided
    ]);

    // Respond with success
    return response()->json(['message' => 'Activity submitted successfully!', 'file_path' => $filePath], 200);
}


}
