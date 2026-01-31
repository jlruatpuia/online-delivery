<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'delivery_id',
        'deliveryboy_id',
        'amount',
        'payment_type',
        'payment_method',
        'upi_ref_no'
    ];

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(User::class, 'deliveryboy_id');
    }
}
