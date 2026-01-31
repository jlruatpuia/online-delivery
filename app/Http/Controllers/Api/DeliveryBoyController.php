<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryBoyController extends Controller
{
    public function activate(User $user)
    {
        $user->update(['is_active' => true]);

        return response()->json([
            'message' => 'Delivery boy activated'
        ]);
    }

    public function deactivate(User $user)
    {
        $user->update(['is_active' => false]);

        return response()->json([
            'message' => 'Delivery boy deactivated'
        ]);
    }
}
