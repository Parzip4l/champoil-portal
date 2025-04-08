<?php

namespace App\Company;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetupChecklist extends Model
{
    use HasFactory;

    protected $fillable = ['company_code', 'key', 'is_completed'];

    public static function defaultSteps()
    {
        return [
            'company_profile' => 'Atur data perusahaan',
            'first_employee' => 'Tambah karyawan pertama',
            'attendance_setup' => 'Setup lokasi dan absensi',
            'workshift_setup' => 'Setup jam kerja & shift',
            'leave_setup' => 'Setup cuti tahunan',
        ];
    }
}
