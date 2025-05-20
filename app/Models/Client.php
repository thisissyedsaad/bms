<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_id',
        'lifetime_commission',
        'profile_photo',
        'full_name',
        'profile_name',
        'email',
        'tagline',
        'mobile_number',
        'whatsapp_number',
        'is_active',
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

    public function source()
    {
        return $this->belongsTo(Source::class);
    }    
}
