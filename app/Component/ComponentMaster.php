<?php

namespace App\Component;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentMaster extends Model
{
    use HasFactory;
    protected $table = 'additional_component_masters';
    protected $fillable = ['code','title','type','effective_date','company'];
}
