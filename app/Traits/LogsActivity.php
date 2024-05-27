<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use App\Activities\Log;
use App\Employee;

trait LogsActivity
{
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity($model, 'created');
        });

        static::updated(function ($model) {
            self::logActivity($model, 'updated');
        });

        static::deleted(function ($model) {
            self::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity($model, $action)
    {
        if (Auth::check()) {
            $code = Auth::user()->employee_code;
            $company = Employee::where('nik', $code)->first();

            $ipAddress = Request::ip();
            $log = new Log();
            $log->user_id = $code;
            $log->action = ucfirst($action);
            $log->controller = get_class($model) . '@' . $action;
            $log->ip_address = $ipAddress;
            $log->description = $company->unit_bisnis;
            $log->data = $model->toJson();
            $log->save();
        }
    }
}
