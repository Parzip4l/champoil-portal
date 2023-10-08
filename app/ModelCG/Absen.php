<?php

namespace App\ModelCG;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'absens';
    protected $fillable = ['nik','tanggal'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
