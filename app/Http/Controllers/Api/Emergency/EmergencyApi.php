<?php

namespace App\Http\Controllers\Api\Emergency;

use App\Http\Controllers\Controller;
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
use Carbon\Carbon;

// Model
use App\ModelCG\Schedule;
use App\ModelCG\Project;
use App\Absen;
use App\Employee;
use App\User;
use App\Company\CompanyModel;
use App\Emergency\EmergencyCategory;
use App\Emergency\EmergencyModel;
use App\Emergency\EmergencyDetails;

class EmergencyApi extends Controller
{
    public function index(Request $request)
    {
        try {
            // Get the token from the Authorization header
            $token = $request->bearerToken();
            // Check if the token is valid
            $user = Auth::guard('api')->user();
            $company = $user->company;

            if(!$company)
            {
                return response()->json(['error' => 'Terjadi kesalahan.'], 500);
            }

            $emergencyData = EmergencyModel::where('user', $user->name)
                ->where('status', 'active')
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan waktu pembuatan terbaru
                ->first();
            
            if ($emergencyData) {

                $notifiedUser = EmergencyDetails::where('emergency_id',$emergencyData->id)->get();
                return response()->json([
                    'message' => 'Anda memiliki request active',
                    'data' => $emergencyData,
                    'notifiedUser' => $notifiedUser

                ], 200);
            } else {
                $emergencyKategori = EmergencyCategory::where('company',$company)->get();
                return response()->json([
                    'dataCategory' => $emergencyKategori,
                ], 200);
            }

        } catch (\Exception $e) {
            // Handle general errors
            return response()->json(['error' => 'Terjadi kesalahan.'], 500);
        }
    }

    // Emergency Request
    public function EmergencyRequest(Request $request)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $today = now()->toDateString();
            $token = $request->bearerToken();
            
            // Check if the token is valid
            $user = Auth::guard('api')->user();
            // Check Company User
            $company = $user->company;

            $emergencyData = EmergencyModel::where('user', $user->name)
                ->where('status', 'active')
                ->whereDate('created_at', $today)
                ->first();
            
            if($emergencyData)
            {
                return response()->json(['error' => 'Anda Memiliki Request Active.'], 500);
            }

            // Return if user does not have a company
            if (!$company) {
                return response()->json(['error' => 'Terjadi kesalahan.'], 500);
            }

            $schedule = Schedule::where('employee', $user->name)
                ->whereDate('tanggal', $today)
                ->first();

            if (!$schedule) {
                return response()->json(['error' => 'Anda Tidak Ada Schedule Hari Ini.'], 500);
            }

            // Create emergency
            $emergency = new EmergencyModel();
            $emergency->user = $user->name;
            $emergency->latitude = $request->latitude;
            $emergency->longitude = $request->longitude;
            $emergency->category = $request->kategori;
            $emergency->deskripsi = '.';
            $emergency->status = 'active';
            $emergency->company = $company;
            $emergency->save();
            
            $userLatitude = $request->latitude;
            $userLongitude = $request->longitude;

            // Data absen
            $absentUsers = Absen::select('nik', 'longtitude', 'latitude', 'project')
                ->where('tanggal', $today)
                ->where('status', 'H')
                ->whereRaw("(
                    6371 * acos(
                        cos(radians(?)) * cos(radians(latitude)) * 
                        cos(radians(longtitude) - radians(?)) + 
                        sin(radians(?)) * sin(radians(latitude))
                    )
                ) < 5", [$userLatitude, $userLongitude, $userLatitude])
                ->get();


                foreach ($absentUsers as $absentUser) {
                    $details = new EmergencyDetails();
                    $details->emergency_id = $emergency->id;
                    $details->name = $absentUser->nik; // Change as needed to match your application
                    $details->project = $absentUser->project; // Use project from absent user
                    
                    // Calculate and round the distance
                    $distanceInKm = round($this->calculateDistance($request->latitude, $request->longitude, $absentUser->latitude, $absentUser->longtitude));
                    $formattedDistance = $distanceInKm . ' KM'; // Format the output
                    $timeEstimateMinutes = ceil($distanceInKm * 12); // 12 minutes per kilometer
                    $details->time_estimate = $timeEstimateMinutes . ' minutes'; 
                    $details->category = $request->kategori;
                    $details->distance = $formattedDistance; 
                    $details->emergency_status = 'active';
                    $details->request_status = 'pending';
                    $details->save();
                }

            

            DB::commit(); // Commit the transaction

            return response()->json(['message' => 'Your emergency notification has been received. We will provide immediate assistance!.'], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if an error occurs
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // Calculate Distance 
    protected function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radius bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;
        return $distance; // Jarak dalam kilometer
    }
}
