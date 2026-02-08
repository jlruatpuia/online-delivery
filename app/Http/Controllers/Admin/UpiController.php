<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upi;
use Illuminate\Http\Request;

class UpiController extends Controller
{
    public function get() {
        $upi = Upi::updateOrCreate(
            ['id' => 1],
            [
                'upi_id' => '9615333839@okbizaxis',
                'payee_name' => 'Rose Online Shopping'
            ]
        );
        return view('admin.upi', compact('upi'));
    }

    public function update(Request $request) {
        $upi = Upi::updateOrCreate(
            ['id' => $request->id],
            [
                'upi_id' => $request->upi_id,
                'payee_name' => $request->payee_name,
            ]
        );
        return view('admin.upi', compact('upi'));
    }
}
