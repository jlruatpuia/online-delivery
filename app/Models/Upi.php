<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upi extends Model
{
    protected $fillable = [
        'upi_id',
        'payee_name'
    ];
}
