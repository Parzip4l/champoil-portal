<?php

namespace App\Http\Controllers\Taskmanagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatroliController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data=[];
        return view('pages.operational.patroli.index',$data);
    }
}

