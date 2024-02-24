<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;
    protected $table = 'absens';
    protected $fillable = ['user_id', 'nik', 'tanggal', 'clock_in', 'latitude', 'longtitude', 'status','project'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $attributes = [
        'clock_in' => '07:00',
    ];
}
