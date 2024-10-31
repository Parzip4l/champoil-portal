<?php

namespace App\Emergency;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class EmergencyDetails extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $table = 'emergency_details';
    protected $fillable = [
        'emergency_id',
        'name',
        'project',
        'category',
        'distance',
        'time_estimate',
        'emergency_status',
        'request_status',
    ];
    public function emergency()
    {
        return $this->belongsTo(EmergencyModel::class, 'emergency_id', 'id');
    }
}
