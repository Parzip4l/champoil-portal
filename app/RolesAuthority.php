<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolesAuthority extends Model
{
    use HasFactory;
    protected $table = 'roles';
    protected $fillable = ['role_name','description','name'];
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
