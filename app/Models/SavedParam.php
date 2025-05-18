<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedParam extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'params', 'title'];

    public function getParamsAttribute($value)
    {
        return collect(json_decode($value, true));
    }
}
