<?php

namespace App\Activities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;
    protected $table = 'logs';
    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'user_id', 'nik');
    }
}
