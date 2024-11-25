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

function schedule($nik, $tanggal) {
  $schedule = app('App\ModelCG\Schedule');
  
  // Convert $tanggal to a Carbon instance to manipulate it
  $tanggalCarbon = Carbon::parse($tanggal);

  // Check if the time is after 00:00 and adjust the date accordingly
  if ($tanggalCarbon->format('H:i') > '00:00:00') {
      // Subtract one day from the date
      $tanggalCarbon->subDay();
  }

  // Query the schedule for the employee using the potentially adjusted date
  return $schedule->where('employee', $nik)
                  ->where('tanggal', $tanggalCarbon->format('Y-m-d')) // Format date to match 'Y-m-d'
                  ->first();
}

function BarangCicilan(){
  // Retrieve the BarangCicilan model
  $BarangCicilan = app('App\ModelCG\asset\BarangCicilan');
  
  // Initialize an empty string to hold the HTML
  $html = '';

  // Find the employee (karyawan) based on NIK
  $item_master = $BarangCicilan->all();

  // Check if the karyawan exists
  if ($item_master) {
      // Loop through all cicilan items (assumed relationship 'barangCicilanItems')
      foreach ($item_master as $item) {
          // Generate radio input for each item
          $html .= '<input type="radio" name="barang_diajukan" value="' . $item->id . '"> ' . $item->nama_barang . '<br>';
      }
  } else {
      $html = 'No Barang Cicilan found for this NIK.';
  }

  // Return the generated HTML
  return $html;
}

function formatRupiah($key) {
  // Ensure the input is a numeric value
  if (!is_numeric($key)) {
      return "Invalid amount"; // Handle non-numeric input
  }

  // Format the number as Rupiah
  return 'Rp ' . number_format($key, 0, ',', '.'); // 0 decimal places, commas for thousands
}

function BarangCicilanDetail($id){
  // Retrieve the BarangCicilan model
  $BarangCicilan = app('App\ModelCG\asset\BarangCicilan')->where('id',$id)->first();


  // Return the generated HTML
  return $BarangCicilan;
}

function project_all() {
  $projects = app('App\ModelCG\Project')->where('company','Kas')->get();

  // Start building the HTML for the select input
  $html = '<select id="project" name="project" class="form-control select2">';
  $html .= '<option value="">-- Select Project --</option>';

  // Loop through the projects to create options
  foreach ($projects as $project) {
      $html .= '<option value="' . $project->id . '">' . $project->name . '</option>';
  }

  // Close the select input
  $html .= '</select>';

  // Return the HTML
  return $html;
}


function project_filter($project_id){
  $project = app('App\ModelCG\Project');
  if(empty($project_id)){
    $records = $project->where('company','Kas')->get();

    $result = [
        [
            "id" => "project",
            "name" => "Project",
            "parent_id" => null
        ]
    ];
  
    // Add child nodes for each project
    foreach ($records as $project) {
        $result[] = [
            "id" => $project->id,
            "name" => $project->name,
            "parent_id" => "project"
        ];
    }
  }else{
    $result=[];
  }
  

  return $result;

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

function push_slack_message($url,$message){

      // Use the url you got earlier
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
  return $resp;
}

function find_hook_slack($id){
    // Retrieve the BarangCicilan model
    $hook = app('App\Slack')->where('id',$id)->first();
  
  
    // Return the generated HTML
    return $hook['url'];
}