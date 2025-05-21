<?php

namespace App\Backup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenBackup extends Model
{
    use HasFactory;
    protected $table = 'absen_backup';
    protected $fillable = ['user_id', 'nik', 'tanggal', 'clock_in', 'latitude', 'longtitude', 'status','project','photo'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $attributes = [
        'clock_in' => '07:00',
    ];
}
