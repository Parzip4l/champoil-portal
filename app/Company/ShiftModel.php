<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// 
use App\Company\CompanyModel;
use App\Company\WorkLocation;

class ShiftModel extends Model
{
    use HasFactory;
    protected $table = 'company_shifts';
    protected $fillable = ['company_id', 'work_location_id', 'name', 'start_time', 'end_time'];

    public function company() {
        return $this->belongsTo(CompanyModel::class);
    }

    public function workLocation() {
        return $this->belongsTo(WorkLocation::class);
    }
}
