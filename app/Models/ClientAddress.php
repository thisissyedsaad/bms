<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'address_line_1',
        'address_line_2',
        'city',
        'country_id',
        'show_city',
        'show_country'
    ];
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Get the client that owns the address.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the country that owns the address.
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
