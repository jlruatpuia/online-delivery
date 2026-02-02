<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;

class MobileScanController extends Controller
{
    /**
     * Show scan page
     */
    /**
     * Show scanner
     */
    public function index()
    {
        return view('mobile.scan');
    }

    /**
     * Handle scanned QR / Barcode
     * Expected scan value: invoice_no
     */
    public function handle(Request $request)
    {
        $request->validate([
            'scan_result' => 'required|string|max:255',
        ]);

        $invoiceNo = trim($request->scan_result);

        $delivery = Delivery::where('invoice_no', $invoiceNo)->first();

        if (! $delivery) {
            return redirect()
                ->route('mobile.scan')
                ->with('error', 'Invalid invoice number. Delivery not found.');
        }

        if ($delivery->deliveryboy_id !== auth()->id()) {
            return redirect()
                ->route('mobile.scan')
                ->with('error', 'This delivery is not assigned to you.');
        }

//        if (! in_array($delivery->status, ['pending', 'assigned'])) {
//            return redirect()
//                ->route('mobile.scan')
//                ->with('error', 'This delivery is already completed.');
//        }

        return redirect()
            ->route('mobile.delivery.show', $delivery)
            ->with('success', 'Delivery found successfully.');
    }
}
