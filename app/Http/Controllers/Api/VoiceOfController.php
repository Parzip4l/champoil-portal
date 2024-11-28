<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\VoiceofGuardians;
use App\ModelCG\VoiceRellations;
use GuzzleHttp\Client;


class VoiceOfController extends Controller
{
    public function index(Request $request){
        $msg="success";
        $error=false;
        $records = VoiceofGuardians::all();

        $list=[];
        $todo=0;
        $prog=0;
        $done=0;
        if(!empty($records)){
            foreach($records as $row){
                
                if($row->status == 1){
                    $label="On Progress";
                    $prog  +=1;
                }else if($row->status == 2){
                    $label="Done";
                    $done  +=1;
                }else{
                    $label="To Do";
                    $todo  +=1;
                }
                $list[]=[
                    "id"=>$row->id,
                    "nama"=> $row->nama,
                    "project"=>project_byID($row->project)->name,
                    "created_at"=>date('d F Y H:i:s',strtotime($row->created_at)),
                    "status"=> $label,
                    "rating"=> $row->rating
                ];
            }
        }

        $result=[
            "msg"=>$msg,
            "error"=>$error,
            "todo"=>$todo,
            "prog"=>$prog,
            "done"=>$done,
            "records"=>@$list
        ];

        return response()->json($result, 200);
    }

    public function voice_detail($id){
        $msg="success";
        $error=false;
        $records = VoiceofGuardians::find($id);

        $list=[];
     
        if(!empty($records)){
                $detail =VoiceRellations::where('voice_id',$records->id)->get();
                $list[]=[
                    "id"=>$records->id,
                    "nama"=> $records->nama,
                    "project"=>project_byID($records->project)->name,
                    "nomor_wa"=>$records->nomor_wa,
                    "pertanyaan"=>$records->pertanyaan,
                    "created_at"=>date('d F Y H:i:s',strtotime($records->created_at)),
                    "percakapan"=>$detail
                ];
        }

        $result=[
            "msg"=>$msg,
            "error"=>$error,
            "records"=>@$list
        ];

        return response()->json($result, 200);
    }

    public function submit_voice(Request $request){
        $error = true;  // Default to true (failed)
        $msg = "Failed"; // Default message
    
        try {
            // Validate the request if needed
            $validated = $request->validate([
                'nama' => 'required|string',
                'project' => 'required|string',
                'pertanyaan' => 'required|string',
                'nomor_wa' => 'required',
                'attachment' => 'required|file', // Ensure a file is uploaded
            ]);
    
            // Handle file upload
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                
                // Generate a unique filename with the original extension
                $fileName = time() . '.' . $file->getClientOriginalExtension();
                
                // Store the file in the 'public' disk, which maps to the 'public' directory
                $filePath = $file->storeAs('uploads/attachments', $fileName, 'public'); // Store file in 'public/uploads/attachments'
                
                // Make sure the file is publicly accessible
                $fileUrl = asset('storage/' . $filePath); // Generate the public URL for the file
            } else {
                throw new \Exception("Attachment file is required.");
            }
    
            // Prepare the data for insertion
            $insert = [
                "nama" => $validated['nama'],
                "project" => $validated['project'],
                "pertanyaan" => $validated['pertanyaan'],
                "attachment" => $fileUrl,  // Store the public URL
                "nomor_wa" => $validated['nomor_wa'],
                "status" => 0,
                "created_at" => now(), // Use Laravel's helper function
            ];
    
            // Insert the data into the database
            $query = VoiceofGuardians::insert($insert);
    
            // If insertion is successful, update the error flag and message
            $error = false;
            $msg = "Data successfully submitted.";
    
            // Prepare the result to return
            $result = [
                "msg" => $msg,
                "error" => $error,
                "records" => $insert
            ];
    
            return response()->json($result, 200);
    
        } catch (\Exception $e) {
            // Catch any errors and set the error flag and message
            $msg = $e->getMessage();
            return response()->json([
                "msg" => $msg,
                "error" => $error,
            ], 400);
        }
    }

    public function submit_voice_relations(Request $request){
        $error = false;  // Default to true (failed)
        $msg = "Failed"; // Default message
        $data= $request->all();
    
        try {
            
            $insert=[
                "voice_id"=>$data['voice_id'],
                "voice_user"=>$data['voice_user'],
                "jawaban"=>$data["jawaban"],
                "created_at" => now(),
            ];
    
            // Insert the data into the database
            $query = VoiceRellations::insert($insert);
            if($query && $data['voice_user']==1){
                $url = 'https://waapi.app/api/v1/instances/17816/client/action/send-message';
                $token = 'QB3r7rcz8AhMyvMiYMeP4VAhf0R996eQBmnFLrs627a36a08'; // Replace with your actual token
                $chatId = '6285624038980@c.us';
                $message = 'Feedback anda sudah ddidjawab, kliklink berikut untuk melihat jawaban \n'.route('voice-frontline-detail',['id'=>$query]);

                $client = new Client();

                $response = $client->post($url, [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => "Bearer $token",
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [
                        'chatId' => $chatId,
                        'message' => $message,
                    ],
                ]);
    
                $responseBody = json_decode($response->getBody(), true);
            }
    
            // If insertion is successful, update the error flag and message
            $error = false;
            $msg = "Data successfully submitted.";
    
            // Prepare the result to return
            $result = [
                "msg" => $msg,
                "error" => $error,
                "records" => $insert
            ];
    
            return response()->json($result, 200);
    
        } catch (\Exception $e) {
            // Catch any errors and set the error flag and message
            $msg = $e->getMessage();
            return response()->json([
                "msg" => $msg,
                "error" => $error,
            ], 400);
        }
    }
    
}
