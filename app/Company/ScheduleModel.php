<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Company\CompanyModel;
use App\Company\WorkLocation;
use App\Company\ShiftModel;
use App\Employee;

class ScheduleModel extends Model
{
    use HasFactory;
    protected $table = 'company_schedule';

    protected $fillable = ['company_id', 'employee_id', 'shift_id', 'work_location_id', 'work_date'];

    public function company() {
        return $this->belongsTo(CompanyModel::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }

    public function shift() {
        return $this->belongsTo(ShiftModel::class);
    }

    public function workLocation() {
        return $this->belongsTo(WorkLocation::class);
    }
}
