<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoltageData extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan oleh model ini
    protected $table = 'voltages';

    // Tentukan kolom yang boleh diisi (mass assignment)
    protected $fillable = ['voltage'];

    // Jika menggunakan timestamps, Anda bisa mengaktifkan fitur ini (optional)
    public $timestamps = true;
}
