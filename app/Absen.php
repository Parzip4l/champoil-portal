<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $attributes = [
        'clock_in' => '07:00',
    ];
}
