<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function storeLog($action, $description = null)
    {
        $user = Auth::guard('api')->user(); // Ambil user dari guard API
        $log = new Log();
        $log->user_id = Auth::check() ? Auth::id() : null; // ID user jika ada
        $log->action = $action;
        $log->description = $description;
        $log->save();
    }
}
