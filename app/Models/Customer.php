<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone_no',
        'map_location',
        'local_id'
    ];

    public function deliveries() {
        return $this->hasMany(Delivery::class);
    }
}
