<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    protected $fillable = [
        'invoice_no',
        'sales_date',
        'amount',
        'payment_type',
        'customer_id',
        'deliveryboy_id',
        'status'
    ];

    public function deliveryBoy()
    {
        return $this->belongsTo(User::class, 'deliveryboy_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
