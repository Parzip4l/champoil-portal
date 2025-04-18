<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Company\WorkLocation;
use App\Company\ShiftModel;

class Absen extends Model
{
    use HasFactory;
    protected $table = 'absens';
    protected $fillable = ['user_id', 'nik', 'tanggal', 'clock_in', 'latitude', 'longtitude', 'status','project','photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'nik');
    }

    public function workLocation()
    {
        return $this->belongsTo(WorkLocation::class, 'work_location_id');
    }

    public function shift()
    {
        return $this->belongsTo(ShiftModel::class, 'shift_id', 'id');
    }

    protected $attributes = [
        'clock_in' => '07:00',
    ];
}
