<?php
use Carbon\Carbon;
use Carbon\CarbonPeriod;

function tanggal_bulan($year,$month){
  
  // Determine the start date
  $startDate = Carbon::create($year, $month, 21)->subMonth();
  // Determine the end date
  $endDate = Carbon::create($year, $month, 20);

  // Create the period
  $period = CarbonPeriod::create($startDate, $endDate);

  // Collect the dates
  $dates = [];
  foreach ($period as $date) {
      $dates[] = $date->format('Y-m-d');
  }

  return $dates;
}

function bulan(){
  // Membuat array kosong untuk menampung nama-nama bulan
  $months = [];

  // Mengisi array dengan nama-nama bulan
  for ($i = 1; $i <= 12; $i++) {
      $months[] = Carbon::create()->month($i)->format('F');
  }

  return $months;

}
  
function active_class($path, $active = 'active') {
  return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

function is_active_route($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
}

function show_class($path) {
  return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
}

function karyawan_bynik($nik){
  $karyawan = app('App\Employee');
  return $karyawan->where('nik',$nik)->first();
}


function project_byID($id){
  $project = app('App\ModelCG\Project');
  return $project->where('id',$id)->first();
}

if (!function_exists('insert_line_breaks')) {
  function insert_line_breaks($text, $interval = 30) {
      return preg_replace('/(.{1,' . $interval . '})(\s+|$)/u', '\\1<br>', $text);
  }
}

function calculateDistance($lat1, $lon1, $lat2, $lon2){
      $earthRadius = 6371000; // Jari-jari bumi dalam meter

      $dLat = deg2rad($lat2 - $lat1);
      $dLon = deg2rad($lon2 - $lon1);
      
      $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
      $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
      
      $distance = round($earthRadius * $c); 

      return $distance;
}

function push_notif_wa($data,$token,$instance,$nomor,$url){
    $params=array(
      'token' => 'd2f0eeqxiklsbg4r',
      'to' => $nomor,
      'body' => $data
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://api.ultramsg.com/instance40031/messages/chat",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 1000,
      CURLOPT_SSL_VERIFYHOST => 0,
      CURLOPT_SSL_VERIFYPEER => 0,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => http_build_query($params),
      CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);
    
    curl_close($curl);
    
    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
}