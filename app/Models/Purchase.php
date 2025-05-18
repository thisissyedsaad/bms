<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['customer_id', 'advertisement_id', 'type', 'amount', 'payment_card_id', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => '€ ' . $value,
        );
    }
}
