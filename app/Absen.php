<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;
    protected $table = 'absens';
    protected $fillable = ['nik','tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
