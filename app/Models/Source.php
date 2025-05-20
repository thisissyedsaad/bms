<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Source extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'commission_type',
        'commission_value',
        'is_platform',
        'notes',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }     

    public function clients()
    {
        return $this->hasMany(Client::class);
    }    
}
