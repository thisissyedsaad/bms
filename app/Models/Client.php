<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_photo',
        'full_name',
        'profile_name',
        'email',
        'tagline',
        'mobile_number',
        'whatsapp_number',
        'platform',
        'website',
        'reference_by',
        'profile_description',
        'created_by',
        'updated_by'
    ];

    public function address()
    {
        return $this->hasOne(ClientAddress::class);
    }  
    
    public function projects()
    {
        return $this->hasMany(Project::class);
    }      
}
