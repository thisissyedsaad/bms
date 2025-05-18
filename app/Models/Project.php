<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'title',
        'conversion_rate',
        'total_amount',
        'received_amount',
        'currency',
        'project_type',
        'hourly_rate',
        'estimated_hours',
        'details',
        'assigned_to',
        'start_date',
        'end_date',
        'platform',
        'status',
        'created_by',
        'updated_by',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }    
}
