<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Employee;

class CompanySetting extends Model
{
    use HasFactory;
    protected $table = 'company_settings';
    protected $fillable = ['company_id', 'key', 'value','updated_by'];

    public function company()
    {
        return $this->belongsTo(CompanyModel::class);
    }

    // Helper to get value as boolean
    public function getValueAttribute($value)
    {
        $decoded = json_decode($value, true);
        return is_null($decoded) ? $value : $decoded;
    }

    public function setValueAttribute($value)
    {
        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }

    public function updatedBy()
    {
        return $this->belongsTo(Employee::class, 'updated_by', 'nik');
    }

}
