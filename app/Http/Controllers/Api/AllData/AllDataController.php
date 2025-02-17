<?php

namespace App\Http\Controllers\Api\AllData;

use App\Http\Controllers\Controller;
// Modul
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response as ResponseXls;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ForgotPasswordMail;
use PDF;
// Model
use App\Employee;
use App\Paklaring;
use App\EmployeeResign;
use App\User;
use App\ResetPassword;
use App\Pengumuman\Pengumuman;
use App\News\News;
use App\ModelCG\Birthday;
use App\ModelCG\asset\PengajuanCicilan;
use App\ModelCG\asset\BarangCicilan;
use App\Loan\LoanModel;
use App\ModelCG\Payroll;
use App\ModelCG\Schedule;
use App\ModelCG\ScheduleBackup;
use App\ModelCG\Project;
use App\ModelCG\ProjectDetails;
use App\Koperasi\LoanPayment;
use App\Koperasi\Koperasi;
use App\Koperasi\Anggota;
use App\Koperasi\Loan;
use App\VoltageData;

class AllDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function ListPengumuman(Request $request)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            $organisasiUser = Employee::where('nik', $employeeCode)->value('organisasi');
            $tanggal_sekarang = now()->format('Y-m-d');
            $pengumuman = Pengumuman::where('end_date', '>=', $tanggal_sekarang)
                            ->where(function ($query) use ($organisasiUser) {
                                $query->where('tujuan', $organisasiUser)
                                    ->orWhere('tujuan', 'semua');
                            })
                            ->where('company',$unitBisnis)
                            ->get();
            if(!empty($pengumuman)){
                foreach($pengumuman as $row){
                    $row->attachments = asset($row->attachments);
                }
            }
            return response()->json(['dataPengumuman' => $pengumuman], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }
    }

    public function showPengumuman($id)
    {
        try {
            // Retrieve the token from the request
            $token = request()->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            // Cari pengumuman berdasarkan ID dan perusahaan (company)
            $pengumuman = Pengumuman::where('id', $id)
                                    ->where('company', $unitBisnis)
                                    ->first();

            if (!$pengumuman) {
                return response()->json(['error' => 'Pengumuman tidak ditemukan'], 404);
            }

            return response()->json(['dataPengumuman' => $pengumuman], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function ListBerita(Request $request)
    {
        try {
            // Retrieve the token from the request
            $token = $request->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $tanggal_sekarang = now()->format('Y-m-d');
            $berita = News::where('company', $unitBisnis)->get();

            return response()->json(['dataBerita' => $berita], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating request: ' . $e->getMessage()], 500);
        }   
    }

    public function showBerita($id)
    {
        try {
            // Retrieve the token from the request
            $token = request()->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            // Cari berita berdasarkan ID dan perusahaan (company)
            $berita = News::where('id', $id)
                        ->where('company', $unitBisnis)
                        ->first();

            if (!$berita) {
                return response()->json(['error' => 'Berita tidak ditemukan'], 404);
            }
            $berita->featuredimage = url('images/featuredimage/' . $berita->featuredimage);
            return response()->json(['dataBerita' => $berita], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function storeVoltage(Request $request)
    {
        // Validasi data yang diterima
        $validated = $request->validate([
            'voltage' => 'required|numeric',
        ]);

        // Simpan data ke database
        $voltageData = VoltageData::create([
            'voltage' => $validated['voltage'],
        ]);

        // Kembalikan respon JSON
        return response()->json([
            'message' => 'Voltage data received and saved successfully',
            'data' => $voltageData,
        ], 200);
    }

    public function getVoltageData()
    {
        // Ambil semua data dari tabel voltages
        $voltageData = VoltageData::all();

        // Kembalikan respon JSON dengan data
        return response()->json([
            'message' => 'Voltage data retrieved successfully',
            'data' => $voltageData,
        ], 200);
    }
    
    public function BirtdayList(Request $request)
    {
        try {
            $token = request()->bearerToken();
            // Authenticate the user based on the token
            $user = Auth::guard('api')->user();

            $employeeCode = $user->name;
            $today = now();

            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');

            $birthdays = Employee::where('unit_bisnis', $unitBisnis)
                     ->select('tanggal_lahir','nama','ktp','slack_id')
                     ->get();
                     
            $upcomingBirthdays = $birthdays->filter(function ($employee) use ($today) {
                $birthDate = Carbon::parse($employee->tanggal_lahir)->setYear($today->year);
                $employee->usia = Carbon::parse($employee->tanggal_lahir)->age;
                
                return $birthDate->isToday() || ($birthDate->isAfter($today) && $birthDate->diffInDays($today) <= 1);
            });

            if(!empty($upcomingBirthdays->values())){
                foreach($upcomingBirthdays->values() as $row){
                    $cek = Birthday::where('nik',$row->ktp)->count();
                    if($cek === 0){
                        Birthday::insert(['nik'=>$row->ktp]);
                    }
                    
                }
            }

            if (!empty($upcomingBirthdays->values())) {
                foreach ($upcomingBirthdays->values() as $row) {
                    // Check if 'ktp' is not equal to a specific value
                        // Find the birthday record based on 'nik'
                        $record = Birthday::where('nik', $row->ktp)->first();
                        
                        // Proceed if a record is found
                        if (!empty($record)) {
                            // Check if today is the birthday
                            if (date('d') == date('d', strtotime($row->tanggal_lahir))) {
                                $umur = $row->usia;
            
                                // Check if slack_id and messages are not empty
                                if (!empty($row->slack_id) && !empty($record->messages)) {
                                
                                    $message ='{
                                        "blocks": [
                                            {
                                                "type": "section",
                                                "text": {
                                                    "type": "mrkdwn",
                                                    "text": "Selamat Ulang Tahun yang Ke-' . $umur . ', <@' . $row->slack_id . '>!"
                                                }
                                            },
                                            {
                                                "type": "section",
                                                "text": {
                                                    "type": "mrkdwn",
                                                    "text": "'.str_replace(["\r", "\n"], '', $record->messages).'"
                                                }
                                            }
                                        ]
                                    }';
                                    
                                    // Example of pushing message to Slack
                                    // Uncomment the following line to actually send the message
                                //    echo  push_slack_message('https://hooks.slack.com/services/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',$message);
                                $url='https://hooks.slack.com/services/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';   
                                $curl = curl_init($url);
                                   curl_setopt($curl, CURLOPT_URL, $url);
                                   curl_setopt($curl, CURLOPT_POST, true);
                                   curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                                   
                                   $headers = array(
                                       "Content-Type: application/json",
                                   );
                                   curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                                   
                                   
                                   
                                   curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
                                   
                                   //for debug only!
                                   curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                                   curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                                   
                                   $resp = curl_exec($curl);
                                   curl_close($curl);
                                   echo $resp;
                                    // For testing, output the message
                                    // echo $text;
                                }
                            }
                        }
                }
            }
            

            
            

            return response()->json(['EmployeeBirthday' =>$upcomingBirthdays->values()], 200);
  
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function check_nik(Request $request) {
        // Validate the input to ensure NIK is provided
        $request->validate([
            'nik' => 'required|string|max:255',
        ]);
    
        // Fetch the employee data based on NIK
        $employee = Employee::where('nik', $request->input('nik'))->first();
    
        // Check if the employee exists
        if ($employee) {
            // Return success response with the employee data
            return response()->json([
                'status' => 'success',
                'data' => $employee
            ], 200);
        } else {
            // Return failure response if NIK not found
            return response()->json([
                'status' => 'error',
                'message' => 'Employee with the provided NIK not found.'
            ], 404);
        }
    }

    public function pengajuan_hp() {
        
        $pengajuan = PengajuanCicilan::all();
        if($pengajuan){
            foreach($pengajuan as $row){
                $row->nama_lengkap = karyawan_bynik($row->nik)->nama;
                $row->nama_project = project_byID($row->project)->name;
                $row->nama_barang =BarangCicilanDetail($row->barang_diajukan)->nama_barang;
                $row->harga_rupiah =formatRupiah($row->harga);
                $row->tanggal_pengajuan =date('d F Y H:i:s',strtotime($row->created_at));
                if($row->status ==0){
                    $status  = '<span class="badge rounded-pill bg-warning">Pending</span>';
                }else if($row->status ==1){
                    $status  = '<span class="badge rounded-pill bg-success">Approved</span>';
                }else if($row->status ==2){
                    $status  = '<span class="badge rounded-pill bg-danger">Reject</span>';
                }
                $row->url_kyp = asset($row->ktp);
                $row->status  = $status;
            }
        }

    
        // Check if the employee exists
        if ($pengajuan) {
            // Return success response with the employee data
            return response()->json([
                'status' => 'success',
                'data' => $pengajuan
            ], 200);
        } else {
            // Return failure response if NIK not found
            return response()->json([
                'status' => 'error',
                'message' => 'Employee with the provided NIK not found.'
            ], 404);
        }
    }

    public function update_pengajuan(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pengajuan_cicilans,id', // Ensure the ID exists in the database
            'status' => 'required|in:1,2' // Ensure the status is either approved or rejected
        ]);
        
        // Find the pengajuan by ID
        $pengajuan = PengajuanCicilan::join('barang_cicilans', 'barang_cicilans.id', '=', 'pengajuan_cicilans.barang_diajukan')
            ->where('pengajuan_cicilans.id', $request->input('id'))
            ->first();
        
        // Check if pengajuan exists
        if (!$pengajuan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengajuan not found.'
            ], 404);
        }
        
        // Update the status
        PengajuanCicilan::where('id',$request->input('id'))->update(['status' => $request->input('status')]); // Save the changes
        
        // If status is 'approved', perform an additional action
        if ($request->input('status') == 1) {
            $this->saveToLoan($pengajuan);
        }
        
        // Return a success response
        return response()->json([
            'status' => 'success',
            'message' => 'Pengajuan status updated successfully.'
        ], 200);
    }

    public function submit_pengajuan_cicilan(Request $request) {
        // Validate the request data
        $request->validate([
            'nik' => 'required|string|max:255',
            'project' => 'required|string|max:255',
            'nomor_hp' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'ktp' => 'required|file|mimes:jpg,png,pdf|max:2048', // Example validation for file
            'barang_diajukan' => 'required|exists:barang_cicilans,id', // Assuming this refers to the BarangnCicilan model
        ]);
    
        // Get the input data
        $data = $request->all();
    
        // Find the price based on the item being applied for
        $cek_harga = BarangCicilan::where('id',$request->input('barang_diajukan'))->first();
    
        // Check if the item exists
        if (!$cek_harga) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item not found.'
            ], 404);
        }
    
        if ($request->hasFile('ktp')) {
            $file = $request->file('ktp');
            $fileName = time() . '_' . $file->getClientOriginalName(); // Generate a unique file name
            $destinationPath = public_path('uploads/ktp'); // Define the public path
            $file->move($destinationPath, $fileName); // Move the file to the public/uploads/ktp directory
        
            // Save the file path (optional, if you need to store the path in the database)
            $filePath = 'uploads/ktp/' . $fileName;
        }
    
        // Prepare data for insertion
        $insert = [
            "nik" => $data['nik'],
            "project" => $data['project'],
            "nomor_hp" => $data['nomor_hp'],
            "email" => $data['email'],
            "ktp" => isset($filePath) ? $filePath : null, // Store file path or null if no file
            "barang_diajukan" => $data['barang_diajukan'],
            "harga" => $cek_harga->harga,
            "status"=>0,
            "created_at"=>date('Y-m-d H:i:s')
        ];
    
        // Insert the data into the PengajuanCicilan model
        $pengajuan = PengajuanCicilan::insert($insert); // Assuming `create` is used with fillable fields

        
        $url = find_hook_slack(12);

        $namaKaryawan = karyawan_bynik($data['nik'])->nama ?? 'Unknown';
        $projectName = project_byID($data['project'])->name ?? 'Unknown';
        $barangNama = BarangCicilanDetail($data['barang_diajukan'])->nama_barang ?? 'Unknown';
        $tanggal = date('d F Y H:i:s');

        // Building the message
        $message = '{
            "blocks": [
                {
                    "type": "section",
                    "text": {
                        "type": "mrkdwn",
                        "text": "Hallo <@U06QC04FNG1> Teradpat  Pengajuan Cicilan Baru*"
                    }
                },
                {
                    "type": "section",
                    "fields": [
                        {
                            "type": "mrkdwn",
                            "text": "*Nama Lengkap :*\n'.$namaKaryawan.'"
                        },
                        {
                            "type": "mrkdwn",
                            "text": "*Project :*\n'.$projectName.'"
                        },
                        {
                            "type": "mrkdwn",
                            "text": "*Barang :*\n'.$barangNama.'"
                        },
                        {
                            "type": "mrkdwn",
                            "text": "*Tanggal :*\n'.$tanggal.'"
                        }
                    ]
                }
            ]
        }';

       $push_slack  = push_slack_message($url,$message);
    
        // Return success response
        return response()->json([
            'status' => 'success',
            'data' => $push_slack
        ], 201);
    }

    private function saveToLoan($record){
       

        // Hitung besaran cicilan per bulan
        $installmentAmount = $record->perbulan * 3;

        

        // Buat entri pinjaman
        $loan = new LoanModel();
        $loan->employee_id = $record->nik;
        $loan->amount = $installmentAmount;
        $loan->remaining_amount = $installmentAmount;
        $loan->installments = 3;
        $loan->installment_amount = $record->perbulan; // Use the new variable
        $loan->save();
    }

    public function getImei(Request $request){
        // Assuming the IMEI is sent in the request as a parameter
        $imei = $request->input('imei', 'IMEI not provided');

        // Or if it's sent in headers
        $imeiFromHeader = $request->header('IMEI', 'IMEI not provided');

        return response()->json([
            'imei' => $imei,
            'imei_from_header' => $imeiFromHeader,
        ]);
    }

    public function export_absen(Request $request){
        $start = date('Y-m-d');
        $end = date('Y-m-d');

        // Handle 'periode' input
        if (!empty($request->input('periode'))) {
            $periode = $request->input('periode');
            $periode_min_1_month = date('m', strtotime("-1 month", strtotime($periode)));
            $bulan = date('m', strtotime($periode));

            $start = date('Y') . '-' . $periode_min_1_month . '-21';
            $end = date('Y') . '-' . $bulan . '-20';
        }

        // Handle 'tanggal' input
        if (!empty($request->input('tanggal'))) {
            $tanggal = $request->input('tanggal');
            $explode = explode(' to ', $tanggal);
            $start = $explode[0];
            $end = $explode[1];
        }

        // Fetch projects
        $projects = Project::whereNull('deleted_at')
            ->where('company', 'Kas')
            ->orderBy('name', 'asc')
            ->get();

        if ($projects->isEmpty()) {
            return response()->json(['error' => 'No projects found'], 404);
        }

        // Initialize spreadsheet
        $spreadsheet = new Spreadsheet();
        $spreadsheet->removeSheetByIndex(0); // Remove the default sheet

        // Style configuration
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '003366'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ];

        // Define headers
        $headers = [
            'A1' => 'NO',
            'B1' => 'NAMA',
            'C1' => 'NIK',
            'D1' => 'JABATAN',
            'E1' => 'PROJECT',
            'F1' => 'BPJS TK RATE',
            'G1' => 'STATUS',
            'H1' => 'MARITAL STATUS',
            'I1' => 'JOIN DATE',
            'J1' => 'RESIGN DATE',
            'K1' => 'MONTH COUNT',
            'L1' => 'PENDAPATAN',
            'M1' => 'POTONGAN',
            'N1' => 'TAKE HOME PAY',
        ];

        // Create sheets for each project
        foreach ($projects as $project) {
            // Create a new sheet
            $sheet = $spreadsheet->createSheet();
            $sheet->setTitle($project->name);

            // Add headers
            foreach ($headers as $cell => $label) {
                $sheet->setCellValue($cell, $label);
            }

            // Apply header styles
            $headerRange = 'A1:N1';
            $sheet->getStyle($headerRange)->applyFromArray($headerStyle);

            // Set auto size for all columns
            foreach (range('A', 'N') as $column) {
                $sheet->getColumnDimension($column)->setAutoSize(true);
            }

            // Example data rows (replace this with actual project data)
            $data = [
                ['1', 'John Doe', '12345', 'Manager', 'hh', '2%', 'Active', 'Married', '2022-01-01', null, '12', '10,000,000', '1,000,000', '9,000,000'],
                ['2', 'Jane Smith', '67890', 'Supervisor','hh', '1.5%', 'Resigned', 'Single', '2021-06-01', '2023-05-31', '23', '8,000,000', '800,000', '7,200,000'],
            ];

            // Add data rows
            $rowNumber = 2;
            foreach ($data as $row) {
                $sheet->fromArray($row, null, 'A' . $rowNumber);
                $rowNumber++;
            }
        }

        // Save the file
        $fileName = 'absen_frontline.xlsx';
        $publicDir = public_path('reports');
        if (!file_exists($publicDir)) {
            mkdir($publicDir, 0755, true);
        }
        $filePath = $publicDir . '/' . $fileName;
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return response()->json([
            'error' => false,
            'url' => url('reports/' . $fileName)
        ], 200);
    }


    public function export_payroll(Request $request){
        try {
            $employeeCode = $request->employee;
            if (!$employeeCode) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $periode = $request->month;
            $tahun = date('Y', strtotime($periode));
            $bulan = date('m', strtotime($periode));
            $startDate = Carbon::create($tahun, $bulan, 21)->format('d-m-Y');
            $endDate = Carbon::create($tahun, $bulan, 20)->addMonth()->format('d-m-Y');
            $unitBisnis = Employee::where('nik', $employeeCode)->value('unit_bisnis');
            $organisasiUser = Employee::where('nik', $employeeCode)->value('organisasi');
            // Initialize Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('DATA GAJI');
            $spreadsheet->createSheet();
            $backupSheet = $spreadsheet->setActiveSheetIndex(1);  // Switch to the second sheet
            $backupSheet->setTitle('BACK UP');
            $headers = [
                'A6' => 'NO',
                'B6' => 'NAMA',
                'C6' => 'NIK',
                'D6' => 'JABATAN',
                'E6' => 'PROJECT',
                'F6' => 'BPJS TK RATE',
                'G6' => 'STATUS',
                'H7' => 'TANGGUNGAN',
                'I7' => 'JOIN DATE',
                'J6' => 'RESIGN DATE',
                'K6' => 'MONTH COUNT',
                'L7' => 'GAJI POKOK',
                'M7' => 'TUNJANGAN KERJA',
                'N7' => 'TUNJANGAN LAIN LAIN',
                'O7' => 'PENCAIRAN SIMP. KOP-KAS',
                'P7' => 'BPJS KETENAGAKERJAAN',
                'Q7' => 'BPJS KETENAGAKERJAAN TAXABLE',
                'R7' => 'BACK UP',
                'S7' => 'LAIN-LAIN',
                'T7' => 'JUMLAH PENDAPATAN',
                'U7' => 'ABSENSI',
                'V7' => 'SP',
                'W7' => 'KOP-KAS',
                'X7' => 'DIKSAR',
                'Y7' => 'PINJAMAN',
                'Z7' => 'LAIN-LAIN',
                'AA6' => 'TAKE HOME PAY',
                'AB6' => 'GROSS SALARIES ANNUALIZE',
                'AC6' => 'TER',
                'AD6' => 'TARIF',
                'AE6' => 'INCOME TAXES',
                'AF6' => 'EMAIL'
            ];            
            $headers['H6'] = 'MARITAL STATUS';
            $headers['L6'] = 'PENDAPATAN';
            $headers['U6'] = 'POTONGAN';
            // Set up the merging of H6:I6 for Marital Status
            $sheet->mergeCells('H6:I6');
            $sheet->mergeCells('L6:T6');
            $sheet->mergeCells('U6:Z6');
            $sheet->mergeCells('A6:A7');
            $sheet->mergeCells('K6:K7');
            $sheet->mergeCells('J6:J7');
            $sheet->mergeCells('B6:B7');
            $sheet->mergeCells('C6:C7');
            $sheet->mergeCells('D6:D7');
            $sheet->mergeCells('E6:E7');
            $sheet->mergeCells('F6:F7');
            $sheet->mergeCells('G6:G7');
            $sheet->mergeCells('AA6:AA7');
            $sheet->mergeCells('AB6:AB7');
            $sheet->mergeCells('AC6:AC7');
            $sheet->mergeCells('AD6:AD7');
            $sheet->mergeCells('AE6:AE7');
            $sheet->mergeCells('AF6:AF7');
            
        
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],  // White text color
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '003366'],  // Green background color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],  // Black border color
                    ],
                ],
            ];
            
            foreach ($headers as $cell => $label) {
                // Set header text
                $sheet->setCellValue($cell, $label);
                
                // Apply style to header
                $sheet->getStyle($cell)->applyFromArray($headerStyle);
                $columnLetter = preg_replace('/[0-9]/', '', $cell); // Remove digits to get column letter
                // Set auto size for columns
                $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }
            $get_payrol = Payroll::where('periode',$startDate.' - '.$endDate)->get();
            $result=[];
            foreach($get_payrol as $p){
                $schedule = Schedule::where('employee',$p->employee_code)->where('tanggal',date('Y-m-d',strtotime($endDate)))->first();
                $project = "-";
                $p_gajipokok =0;
                $p_bpjstk = 0;
                $p_bpjs_ks = 0;
                $p_thr = 0;
                $p_tkerja = 0;
                $p_tseragam = 0;
                $p_tlain = 0;
                $p_training = 0;
                $p_operasional = 0;
                $p_membership = 0;
                $r_deduction = 0;
                $p_deduction = 0;
                $tp_gapok = 0;
                $tp_bpjstk = 0;
                $tp_bpjsks = 0;
                $tp_thr = 0;
                $tp_tunjangankerja = 0;
                $tp_tunjanganseragam = 0;
                $tp_tunjanganlainnya = 0;
                $tp_training = 0;
                $tp_operasional = 0;
                $tp_ppn = 0;
                $tp_pph = 0;
                $tp_cashin = 0;
                $tp_total = 0;
                $tp_membership = 0;
                $tp_bulanan = 0;
                $rate_harian = 0;
                $lembur_rate = 0;
                if(!empty($schedule->project)){
                    $projects_name = Project::where('id',$schedule->project)->first();
                    $project_detail = ProjectDetails::where('project_code',$schedule->project)->where('jabatan',karyawan_bynik($p->employee_code)->jabatan)->first();
                    $p_gajipokok = $project_detail->tp_bulanan ?? 0;
                    $p_bpjstk = $project_detail->p_bpjstk ?? 0;
                    $p_bpjs_ks = $project_detail->p_bpjs_ks ?? 0;
                    $p_thr = $project_detail->p_thr ?? 0;
                    $p_tkerja = $project_detail->p_tkerja ?? 0;
                    $p_tseragam = $project_detail->p_tseragam ?? 0;
                    $p_tlain = $project_detail->p_tlain ?? 0;
                    $p_training = $project_detail->p_training ?? 0;
                    $p_operasional = $project_detail->p_operasional ?? 0;
                    $p_membership = $project_detail->p_membership ?? 0;
                    $r_deduction = $project_detail->r_deduction ?? 0;
                    $p_deduction = $project_detail->p_deduction ?? 0;
                    $tp_gapok = $project_detail->tp_gapok ?? 0;
                    $tp_bpjstk = $project_detail->tp_bpjstk ?? 0;
                    $tp_bpjsks = $project_detail->tp_bpjsks ?? 0;
                    $tp_thr = $project_detail->tp_thr ?? 0;
                    $tp_tunjangankerja = $project_detail->tp_tunjangankerja ?? 0;
                    $tp_tunjanganseragam = $project_detail->tp_tunjanganseragam ?? 0;
                    $tp_tunjanganlainnya = $project_detail->tp_tunjanganlainnya ?? 0;
                    $tp_training = $project_detail->tp_training ?? 0;
                    $tp_operasional = $project_detail->tp_operasional ?? 0;
                    $tp_ppn = $project_detail->tp_ppn ?? 0;
                    $tp_pph = $project_detail->tp_pph ?? 0;
                    $tp_cashin = $project_detail->tp_cashin ?? 0;
                    $tp_total = $project_detail->tp_total ?? 0;
                    $tp_membership = $project_detail->tp_membership ?? 0;
                    $tp_bulanan = $project_detail->tp_bulanan ?? 0;
                    $rate_harian = $project_detail->rate_harian ?? 0;
                    $lembur_rate = $project_detail->lembur_rate ?? 0;
                    $project = $projects_name->name ?? '-';
                }
                
                $allowences = json_decode($p->allowences,true);
                $deductions = json_decode($p->deductions,true);
                $backup_sallary = $allowences['totalGajiBackup']??0;
                $lain_lain =$allowences['tunjangan_lain']??0;
                
                $result[] =[
                    'nama'=>karyawan_bynik($p->employee_code)->nama,
                    'nik'=>karyawan_bynik($p->employee_code)->nik,
                    'jabatan'=>karyawan_bynik($p->employee_code)->jabatan,
                    'project'=>$project,
                    'bpjs_tk'=>$p_bpjstk,
                    'status'=>karyawan_bynik($p->employee_code)->status_pernikahan,
                    'tanggungan'=>karyawan_bynik($p->employee_code)->tanggungan,
                    'join_date'=>karyawan_bynik($p->employee_code)->joindate,
                    'resign_date'=>'',
                    'gaji_pokok'=>$p_gajipokok,
                    'tunjangan_kerja'=>$p_tkerja,
                    'tunjangan_lain'=>$p_tlain,
                    'pencairan_koperasi'=>0,
                    'bpjs_tk_tax'=>0,
                    'backup'=>$allowences['totalGajiBackup']??0,
                    'lain_lain'=>$allowences['tunjangan_lain']??0,
                    'jumlah_pendapatan'=>$p_gajipokok+$p_tkerja+0+$p_bpjstk+$backup_sallary+$lain_lain,
                    'absensi'=>$deductions['potongan_absen']??0,
                    'sp'=>$deductions['sp']??0,
                    'koperasi'=>$deductions['iuran_koperasi']??0,
                    'diksar'=>$deductions['potongan_Gp']??0,
                    'pinjaman'=>$deductions['potongan_hutang']??0,
                    'potongan_lain'=>$deductions['potongan_lain']??0,
                    'thp'=>$p->thp,
                    'gross_sallary'=>'',
                    'tier'=>'TIER A',
                    'tarif'=>'',
                    'income_tax'=>'',
                    'email'=>karyawan_bynik($p->employee_code)->email
                ];
            }
            if (!empty($result)) {
                $rowIndex = 8; // Starting from row 2, since row 1 is for headers
                $no=1;
                foreach ($result as $row) {
                    // Populate the data for each row based on the columns
                    $start = Carbon::parse($row['join_date']);  // Tanggal expired sertifikasi
                    $end = Carbon::now();  // Tanggal sekarang
                    // Menghitung total bulan antara tanggal start dan end
                    $totalMonths = $start->diffInMonths($end, false);
                    // Pastikan jika lebih dari 12 bulan, maka kembali ke 1
                    $monthInCycle = ($totalMonths % 12) + 1;
                    // echo "Bulan dalam siklus: $monthInCycle";
                    $sheet->setCellValue('A' . $rowIndex, $no);
                    $sheet->setCellValue('B' . $rowIndex, $row['nama']);
                    $sheet->setCellValue('C' . $rowIndex, "'".$row['nik']);
                    $sheet->setCellValue('D' . $rowIndex, $row['jabatan']);
                    $sheet->setCellValue('E' . $rowIndex, $row['project']);
                    $sheet->setCellValue('F' . $rowIndex, ' 5450000');
                    $sheet->setCellValue('G' . $rowIndex, $row['status']);
                    $sheet->setCellValue('H' . $rowIndex, $row['tanggungan']);
                    $sheet->setCellValue('I' . $rowIndex, $row['join_date']);
                    $sheet->setCellValue('J' . $rowIndex, $row['resign_date']??'-');
                    $sheet->setCellValue('K' . $rowIndex, $monthInCycle);
                    $sheet->setCellValue('L' . $rowIndex, $row['gaji_pokok']);
                    $sheet->setCellValue('M' . $rowIndex, $row['tunjangan_kerja']);
                    $sheet->setCellValue('N' . $rowIndex, $row['tunjangan_lain']);
                    $sheet->setCellValue('O' . $rowIndex, $row['pencairan_koperasi']);
                    $sheet->setCellValue('P' . $rowIndex, $row['bpjs_tk']);
                    $sheet->setCellValue('Q' . $rowIndex, $row['bpjs_tk_tax']);
                    $sheet->setCellValue('R' . $rowIndex, $row['backup']);
                    $sheet->setCellValue('S' . $rowIndex, $row['lain_lain']);
                    $sheet->setCellValue('T' . $rowIndex, $row['jumlah_pendapatan']);
                    $sheet->setCellValue('U' . $rowIndex, $row['absensi']);
                    $sheet->setCellValue('V' . $rowIndex, $row['sp']);
                    $sheet->setCellValue('W' . $rowIndex, $row['koperasi']);
                    $sheet->setCellValue('X' . $rowIndex, $row['diksar']);
                    $sheet->setCellValue('Y' . $rowIndex, $row['pinjaman']);
                    $sheet->setCellValue('Z' . $rowIndex, $row['potongan_lain']);
                    $sheet->setCellValue('AA' . $rowIndex, $row['thp']);
                    $sheet->setCellValue('AB' . $rowIndex, $row['gross_sallary']);
                    $sheet->setCellValue('AC' . $rowIndex, $row['tier']);
                    $sheet->setCellValue('AD' . $rowIndex, $row['tarif']);
                    $sheet->setCellValue('AE' . $rowIndex, $row['income_tax']);
                    $sheet->setCellValue('AF' . $rowIndex, $row['email']);
                    
                    // Move to next row
                    $rowIndex++;
                    $no++;
                }
            }
            $headersBackup = [
                'A1' => 'NO',
                'B1' => 'NAMA',
                'C1' => 'NIK',
                'D1' => 'JABATAN PENGGANTI',
                'E1' => 'PROJECT PENGGANTI',
                'F1' => 'NAMA DIGANTIKAN',
                'G1' => 'JABATAN DIGANTIKAN',
                'H1' => 'PROJECT',
                'I1' => 'TANGGAL',
                'J1' => 'AMOUNT'
            ];
            foreach ($headersBackup as $cell => $label) {
                // Set header text
                $backupSheet->setCellValue($cell, $label);
                
                // Apply style to header
                $backupSheet->getStyle($cell)->applyFromArray($headerStyle);
                $columnLetter = preg_replace('/[0-9]/', '', $cell); // Remove digits to get column letter
                // Set auto size for columns
                $backupSheet->getColumnDimension($columnLetter)->setAutoSize(true);
            }
            $backup = ScheduleBackup::where('periode',date('F-Y', strtotime($endDate)))->get();
            if (!empty($backup)) {
                $indexBackup = 2; // Starting from row 2, since row 1 is for headers
                $no = 1;
            
                foreach ($backup as $back) {
                    // Fetch schedule data
                    $get_schedule = Schedule::where('periode', strtoupper($back->periode))
                                            ->where('tanggal', $back->tanggal)
                                            ->where('employee', $back->man_backup)
                                            ->first();
            
                    // Count employee schedules
                    $count_schedule = Schedule::where('periode', strtoupper($back->periode))
                                              ->where('employee', $back->employee)
                                              ->where('shift', '!=', 'OFF')
                                              ->count();
            
                    // Get salary details for the employee
                    $get_sallary = ProjectDetails::where('jabatan', karyawan_bynik($back->employee)->jabatan)
                                                 ->where('project_code', $back->project)
                                                 ->first();
            
                    $sallary = 0;
                    if (!empty($get_sallary)) {
                        $sallary = $get_sallary->tp_bulanan / max($count_schedule, 1); // Prevent division by zero
                    }
            
                    // Default project is "BKO"
                    $project = "BKO";
                    if (!empty($get_schedule)) {
                        $project = project_byID($get_schedule->project)->name;
                    }
            
                    // Fetch employee and manager details (only once to optimize performance)
                    $manBackup = karyawan_bynik($back->man_backup);
                    $employee = karyawan_bynik($back->employee);
                    $projectDetails = project_byID($back->project);
            
                    // Set cell values
                    $backupSheet->setCellValue('A' . $indexBackup, $no);
                    $backupSheet->setCellValue('B' . $indexBackup, strtoupper($manBackup->nama ?? ''));
                    $backupSheet->setCellValue('C' . $indexBackup, "'" . ($manBackup->ktp ?? ''));
                    $backupSheet->setCellValue('D' . $indexBackup, strtoupper($manBackup->jabatan ?? ''));
                    $backupSheet->setCellValue('E' . $indexBackup, strtoupper($project));
                    $backupSheet->setCellValue('F' . $indexBackup, strtoupper($employee->nama ?? ''));
                    $backupSheet->setCellValue('G' . $indexBackup, $employee->jabatan ?? '');
                    $backupSheet->setCellValue('H' . $indexBackup, strtoupper($projectDetails->name ?? ''));
                    $backupSheet->setCellValue('I' . $indexBackup, $back->tanggal);
                    $backupSheet->setCellValue('J' . $indexBackup, $sallary);
            
                    $indexBackup++;
                    $no++;
                }
            }
            
            // Define file path
            $fileName = 'payroll.xlsx';
            $publicDir = public_path('reports');
            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0755, true); // Create public/reports if not exists
            }
            $filePath = $publicDir . '/' . $fileName;
            // Save file
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
            return response()->json([
                'error' => false,
                'data'=>$result,
                'url' => url('reports/' . $fileName) // URL accessible from the web
            ], 200);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return response()->json(['error' => 'Failed to process export: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error processing request: ' . $e->getMessage()], 500);
        }
    }
    public function paklaring($id){
        try {
            
            // Fetch project details
            $employee = Employee::find($id);

            if (!$employee) {
                return response()->json(['error' => 'employee not found'], 404);
            }

            $resign = EmployeeResign::where('employee_code',$employee->nik)->first();

            $currentYear = date('Y');

            // Fetch the last `nomor` for the current year
            $lastPaklaring = Paklaring::where('tahun', $currentYear)->orderBy('nomor', 'desc')->first();

            if ($lastPaklaring) {
                // Increment the last number
                $newNomor = str_pad($lastPaklaring->nomor + 1, 3, '0', STR_PAD_LEFT);
            } else {
                // Start from 001 if no records found
                $newNomor = '001';
            }

            // Insert the new paklaring record
            $insert = Paklaring::insert([
                "nomor" => $newNomor,
                "tahun" => $currentYear
            ]);

           
            $data = [
                'employee' => $employee,
                'resign' => $resign,
                "nomor" => $newNomor,
                "tahun" => $currentYear
            ];
            

            // Generate the PDF
            $pdf = Pdf::loadView('pages.hc.karyawan_resign.paklaring', $data);
            $pdf->setOption('no-outline', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setPaper('A4', 'portrait');

            // Create unique file name for the PDF
            $fileName = 'paklaring' . $employee->nik . ".pdf";
            $publicPath = public_path('paklaring');

            // Ensure the directory exists
            if (!is_dir($publicPath)) {
                mkdir($publicPath, 0755, true);
            }

            $filePath = $publicPath . '/' . $fileName;

            // Save the PDF
            $pdf->save($filePath);

            $fileUrl = asset('paklaring/' . $fileName);

            // Return JSON response with file details
            return response()->json([
                'message' => 'PDF file generated successfully',
                'path' => $fileUrl,
                'file_name' => $fileName
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return error response
            return response()->json([
                'error' => 'Failed to generate PDF',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function  download_sertifikat($unitBisnis){
        $records = Employee::where('unit_bisnis', $unitBisnis)
                            ->where('resign_status', 0)
                            ->whereNotIn('sertifikasi', ['LAINNYA', 'TIDAK ADA'])
                            ->get();
        $result=[];
        if(!empty($records)){
            foreach($records as $row){
                
                $start = Carbon::now();  // Tanggal hari ini
                $end = Carbon::parse($row->expired_sertifikasi);  // Tanggal masa lalu
                // Menghitung total hari dengan tanda negatif jika perlu
                $totalDays = $start->diffInDays($end, false);
                if($row->expired_sertifikasi  != $row->tanggal_lahir){
                    $result[]=[
                        "nama"=>$row->nama,
                        "ktp"=>$row->ktp,
                        "alamat"=>$row->alamat,
                        "jabatan"=>$row->jabatan,
                        "joindate"=>$row->joindate,
                        "email"=>$row->email,
                        "telepon"=>$row->telepon,
                        "sertifikasi"=>$row->sertifikasi,
                        "expired_sertifikasi"=>$row->expired_sertifikasi,
                        "masa_aktif"=>$totalDays.' Hari',
                    ];
                }
                
            }
        }
        $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('DATA GAJI');
            $headers = [
                'A1' => 'NO',
                'B1' => 'NAMA',
                'C1' => 'NIK',
                'D1' => 'ALAMAT',
                'E1' => 'JABATAN',
                'F1' => 'TANGGAL MASUK',
                'G1' => 'EMAIL',
                'H1' => 'TELEPON',
                'I1' => 'SERTIFIKASI',
                'J1' => 'TANGGAL AKTIF',
                'K1' => 'MASA AKTIF',
            ];            
        
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],  // White text color
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '003366'],  // Green background color
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],  // Black border color
                    ],
                ],
            ];
            
            foreach ($headers as $cell => $label) {
                // Set header text
                $sheet->setCellValue($cell, $label);
                
                // Apply style to header
                $sheet->getStyle($cell)->applyFromArray($headerStyle);
                $sheet->getColumnDimension($cell[0])->setAutoSize(true);
            }
            if (!empty($result)) {
                $rowIndex = 2; // Starting from row 2, since row 1 is for headers
                $no=1;
                foreach ($result as $row) {
                    // Populate the data for each row based on the columns
                    $sheet->setCellValue('A' . $rowIndex, $no);
                    $sheet->setCellValue('B' . $rowIndex, $row['nama']);
                    $sheet->setCellValue('C' . $rowIndex, "'" .$row['ktp']);
                    $sheet->setCellValue('D' . $rowIndex, $row['alamat']);
                    $sheet->setCellValue('E' . $rowIndex, $row['jabatan']);
                    $sheet->setCellValue('F' . $rowIndex, $row['joindate']);
                    $sheet->setCellValue('G' . $rowIndex, $row['email']);
                    $sheet->setCellValue('H' . $rowIndex, "'" .$row['telepon']);
                    $sheet->setCellValue('I' . $rowIndex, $row['sertifikasi']);
                    $sheet->setCellValue('J' . $rowIndex, $row['expired_sertifikasi']);
                    $sheet->setCellValue('K' . $rowIndex, $row['masa_aktif']);
                    
                    
                    // Move to next row
                    $rowIndex++;
                    $no++;
                }
            }
            // / Define file path
            $fileName = 'sertifikasi.xlsx';
            $publicDir = public_path('reports');
            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0755, true); // Create public/reports if not exists
            }
            $filePath = $publicDir . '/' . $fileName;
            // Save file
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
        return response()->json([
            'error' => false,
            'data'=>$result,
            'url' => url('reports/' . $fileName) // URL accessible from the web
        ], 200);
    }

    public function forgotPassword(Request $request){


        $check = User::where('email', strtolower($request->email))->first();

        if (!empty($check)) {
            $random_token = Str::random(60); // Generate token acak

            $create = [
                'token_reset' => $random_token,
                'email_user' => $request->email, // Sesuaikan dengan sumber email
            ];
            
            $token_reset = ResetPassword::create($create);

            $resetLink = url('/form-forgot-password/' . $random_token);

            // Kirim email
            Mail::to($check->email)->send(new ForgotPasswordMail($resetLink));



            $error =false;
            $message =$check;
        } else {
            $error =true;
            $message ="Email Tidak Terdaftar";
        }

        return response()->json([
            'error' => $error,
            'message' => $message
        ], 200);
    }

    public function submitforgotPassword(Request $request){
        // Validasi input
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed', // password harus dikonfirmasi
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validator->errors()->first()
            ], 400);
        }

        // Cek token
        $check = ResetPassword::where('token_reset', $request->token)
            ->where('status', 0)
            ->first();

        if (!$check) {
            return response()->json([
                'error' => true,
                'message' => "Token tidak valid atau sudah digunakan."
            ], 200);
        }

        // Cek apakah token masih berlaku (5 menit)
        if (Carbon::parse($check->created_at)->addMinutes(5)->isPast()) {
            // Update status menjadi kadaluarsa (status = 3)
            $check->update(['status' => 3]);

            return response()->json([
                'error' => true,
                'message' => "Token reset password telah kadaluarsa. Silakan minta ulang."
            ], 410);
        }

        // Update password user
        $user = User::where('email', $check->email_user)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Tandai token sebagai telah digunakan (status = 1)
        $check->update(['status' => 1]);

        return response()->json([
            'error' => false,
            'message' => "Password berhasil direset. Silakan login dengan password baru."
        ], 200);
    }

    public function exportAbsens(Request $request) {
        try {
            $company = $request->company;
            if (!$company) {
                return response()->json(['error' => 'Unauthorized: Company not found'], 401);
            }

            $projects = Project::select('id','name')->where('company',$company)->whereNull('deleted_at')->get();
            $monthYear = date('m-Y',strtotime($request->month));
            $fomatPeriode = date('F-Y',strtotime($request->month));

            // Parse the month and year
            list($month, $year) = explode('-', $monthYear);

            // Create a Carbon instance for the 1st day of the current month
            $startDate = Carbon::create($year, $month, 1);

            // Set the start date to the 21st of the previous month
            $startDate = $startDate->subMonth()->day(21);

            // Set the end date to the 20th of the current month
            $endDate = $startDate->copy()->addMonth()->day(20);

            // Generate the array of dates in d-m-Y format
            $datesArray = [];
            $dateSchedule=[];
            $currentDate = $startDate->copy();

            while ($currentDate <= $endDate) {
                $datesArray[] = $currentDate->format('d-m-Y');
                $dateSchedule[] = $currentDate->format('Y-m-d');
                $currentDate->addDay(); // Move to the next day
            }
    
            // Initialize Spreadsheet
            $spreadsheet = new Spreadsheet();

            // Loop through the companies and create a worksheet for each
            foreach ($projects as $index => $company) {

                // get employee
                $employeeProject = Schedule::select('employee', DB::raw('COUNT(*) as schedule_count'))
                                            ->where('project',$company->id)
                                            ->where('periode', strtoupper($fomatPeriode))
                                            ->groupBy('employee')
                                            ->get();


                // Create a new worksheet for each company
                if ($index > 0) {
                    $spreadsheet->createSheet();
                }

                $headers['A6'] = 'NAMA LENGKAP';
                $headers['B6'] = 'NIK';
                $headers['C6'] = 'EMAIL';
                
                
                // Adding days from $days_array into the headers
                $columnIndex = 4; // Starting at column D (index 4)
                foreach ($datesArray as $day) {
                    $column = $this->getExcelColumn($columnIndex); // Get the correct Excel column letter(s)
                    $headers["{$column}6"] = $day;
                    $columnIndex++;
                }



                // Set the current sheet
                $sheet = $spreadsheet->setActiveSheetIndex($index);
                $sheetTitle = substr($company->name, 0, 31);
                // Set title as the company's name
                $sheet->setTitle($sheetTitle);

                $headerStyle = [
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],  // White text color
                        'size' => 12,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '003366'],  // Green background color
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],  // Black border color
                        ],
                    ],
                ];
                
                foreach ($headers as $cell => $label) {
                    // Set header text
                    $sheet->setCellValue($cell, $label);
                    
                    // Apply style to header
                    $sheet->getStyle($cell)->applyFromArray($headerStyle);
                    $columnLetter = preg_replace('/[0-9]/', '', $cell); // Remove digits to get column letter
                    // Set auto size for columns
                    $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
                }

                $rowIndex = 7;
                foreach ($employeeProject as $employee) {
                    
                    // Assuming employee columns 'employee', 'nik', 'email' need to be added
                    if(!empty(karyawan_bynik($employee->employee)->nama)){
                        $sheet->setCellValue("A{$rowIndex}", karyawan_bynik($employee->employee)->nama ?? '-') // Employee name
                        ->setCellValue("B{$rowIndex}", "'".$employee->employee)        // NIK
                        ->setCellValue("C{$rowIndex}", karyawan_bynik($employee->employee)->email);     // Email
                    }
                    
                    $getSchedule = Schedule::whereIn('tanggal',$dateSchedule)->where('employee',$employee->employee)->get();

                    // Insert schedule counts for each day in the row
                    $columnIndex = 4; // Start from column D for the schedule counts
                    foreach ($datesArray as $day) {
                        $formattedDate = date('Y-m-d', strtotime($day)); // Ensure the date is in Y-m-d format

    // Find the schedule for the given employee and date
    $scheduleForDay = $getSchedule->firstWhere('tanggal', $formattedDate);
                        // Check if there is a schedule for the day, and display the shift
                        if ($scheduleForDay) {
                            $shift = $scheduleForDay->shift; // Assuming `shift` column exists
                            $sheet->setCellValue("{$column}{$rowIndex}", $shift); // Display shift in the cell
                        } else {
                            $sheet->setCellValue("{$column}{$rowIndex}", 'No Shift'); // If no schedule exists for this day
                        }

                        $columnIndex++;
                    }

                    // Move to the next row
                    $rowIndex++;
                }
            }

            // Set the active sheet back to the first sheet
            $spreadsheet->setActiveSheetIndex(0);
    
            // Define file path
            $fileName = 'absensi_frontline.xlsx';
            $publicDir = public_path('reports');
    
            if (!file_exists($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
    
            $filePath = $publicDir . '/' . $fileName;
    
            // Hapus output buffer sebelum menyimpan file
            ob_clean();
    
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
    
            return response()->json([
                'error' => false,
                'project'=>$getSchedule,
                'url' => url('reports/' . $fileName)
            ], 200);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return response()->json(['error' => 'Failed to process export: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error processing request: ' . $e->getMessage()], 500);
        }
    }

    
    function getExcelColumn($index) {
        $column = '';
        while ($index > 0) {
            $index--;
            $column = chr($index % 26 + 65) . $column;
            $index = floor($index / 26);
        }
        return $column;
    }
    
    
}
