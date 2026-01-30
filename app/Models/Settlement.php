<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    protected $fillable = [
        'deliveryboy_id',
        'from_date',
        'to_date',
        'total_amount',
        'status',
        'reject_reason'
    ];

    public function deliveryBoy()
    {
        return $this->belongsTo(User::class, 'deliveryboy_id');
    }
}
