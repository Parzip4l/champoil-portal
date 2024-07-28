<?php

namespace App\Helpers;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogHelper
{
    public static function storeLog($action, $description = null)
    {
        $log = new Log();
        $log->user_id = Auth::check() ? Auth::id() : null; // ID user jika ada
        $log->action = 'null';
        $log->description = $description;
        $log->save();
    }
}
