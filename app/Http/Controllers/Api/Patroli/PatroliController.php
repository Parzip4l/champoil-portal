<?php

namespace App\Http\Controllers\Api\Patroli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ModelCG\Task;
use App\ModelCG\List_task;
use App\ModelCG\Patroli;
use App\ModelCG\Absen;
use App\ModelCG\Temuan;

class PatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function checklist_task($params) {

        $data['master'] = Task::where('unix_code', $params)->first();
    
        if ($data['master']) {
            $data['master']->list_task = List_task::where('id_master', $data['master']->id)->get();
        }
    
        // Return a JSON response
        return response()->json($data);
    }
}
