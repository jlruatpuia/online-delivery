<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = [
        'deliveryboy_id',
        'settlement_date',
        'total_cash',
        'total_upi',
        'total_amount',
        'status',
        'reject_reason'
    ];

    protected $casts = [
        'settlement_date' => 'date',
    ];
    public function deliveryBoy()
    {
        return $this->belongsTo(User::class, 'deliveryboy_id');
    }
}
