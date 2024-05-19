<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Version;

class VersionController extends Controller
{
    public function version(){

        $version = Version::latest('id')->first();
        return response()->json($version);
    }
}
