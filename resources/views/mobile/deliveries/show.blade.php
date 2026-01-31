@extends('mobile.layout')

@section('title', 'Delivery Details')

@section('content')
    <!-- DELIVERY STATUS -->
    @php
        $statusMap = [
            'pending' => ['warning-subtle', 'Pending'],
            'assigned' => ['primary-subtle', 'Assigned'],
            'delivered' => ['success-subtle', 'Delivered'],
            'cancelled' => ['danger-subtle', 'Cancelled'],
            'reschedule_requested' => ['secondary-subtle', 'Reschedule Requested'],
            'cancel_requested' => ['danger-subtle', 'Cancel Requested'],
        ];

        [$color, $label] = $statusMap[$delivery->status]
            ?? ['dark', ucfirst($delivery->status)];
        $hasMap = !empty($delivery->customer->map_location);
    @endphp
    <div class="mt-5 pt-4">
    <!-- INVOICE & AMOUNT -->
    <div class="card shadow-sm mb-3 bg-{{ $color }}">
        <div class="card-header">{{ $label }}</div>
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <div class="text-muted small">Invoice No</div>
                    <h6 class="mb-0">#{{ $delivery->invoice_no }}</h6>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Amount</div>
                    <h6 class="mb-0 text-success">
                        ₹ {{ number_format($delivery->amount, 2) }}
                    </h6>
                </div>
            </div>
        </div>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="card shadow-sm mb-3">
        <div class="card-header fw-semibold">Customer</div>
        <div class="card-body">
            <div class="mb-2">
                <i class="bi bi-person me-2"></i>
                {{ $delivery->customer->name }}
            </div>

            <div class="mb-2">
                <i class="bi bi-telephone me-2"></i>
                <a href="tel:{{ $delivery->customer->phone_no }}"
                   class="text-decoration-none">
                    {{ $delivery->customer->phone_no }}
                </a>
            </div>

            <div class="mb-3">
                <i class="bi bi-geo-alt me-2"></i>
                {{ $delivery->customer->address }}
            </div>


        </div>
        <div class="card-footer d-flex justify-content-between">
            <!-- ACTION BUTTONS -->
                <a href="tel:{{ $delivery->customer->phone_no }}"
                   class="btn btn-outline-primary">
                    📞 Call Customer
                </a>

            @if($hasMap)
                <a href="https://www.google.com/maps?q={{ $delivery->customer->map_location }}"
                   target="_blank"
                   class="btn btn-primary">
                    📍 Navigate
                </a>
            @else
                <button class="btn btn-secondary" disabled>
                    📍 Navigate (N/A)
                </button>
            @endif
        </div>
    </div>

    <!-- ACTION SECTION -->
    @if(in_array($delivery->status, ['pending','assigned']))

        {{-- ✅ PREPAID DELIVERY --}}
        @if($delivery->payment_type === 'prepaid')

            <form method="POST"
                  action="{{ route('mobile.delivery.confirm.prepaid', $delivery) }}">
                @csrf

                <button class="btn btn-success w-100">
                    ✅ Confirm Delivery
                </button>
            </form>

            {{-- 💰 CASH ON DELIVERY --}}
        @else

            <form method="POST"
                  action="{{ route('mobile.delivery.collect.cod', $delivery) }}">
                @csrf

                <h6 class="fw-bold mb-2">Collect Payment</h6>

                <!-- Payment Method Selection -->
                <div class="mb-3">
                    <select name="payment_method"
                            class="form-select"
                            onchange="toggleUpi(this.value)"
                            required>
                        <option value="">Select Payment Method</option>
                        <option value="cash">Cash</option>
                        <option value="upi">UPI</option>
                    </select>
                </div>

                <!-- UPI Section -->
                <div id="upiBox" style="display:none">

                    <div class="text-center mb-2">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=merchant@upi&am={{ $delivery->amount }}"
                             class="img-fluid">
                    </div>

                    <input type="text"
                           name="upi_ref_no"
                           class="form-control mb-2"
                           placeholder="UPI Reference No (optional)">
                </div>

                <button class="btn btn-primary w-100">
                    💰 Submit Payment (₹ {{ number_format($delivery->amount, 2) }})
                </button>

            </form>

        @endif

    @else
        <div class="alert alert-info text-center">
            This delivery is already
            <strong>{{ ucfirst($delivery->status) }}</strong>.
        </div>
    @endif

    <div class="offcanvas offcanvas-bottom"
         tabindex="-1"
         id="requestCanvas">
        <div class="offcanvas-header">
            <h5>Request Change</h5>
            <button class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>

        <div class="offcanvas-body">
            <form method="POST"
                  action="{{ route('mobile.delivery.request', $delivery) }}">
                @csrf

                <select name="type" class="form-select mb-2" required>
                    <option value="">Select</option>
                    <option value="reschedule">Reschedule</option>
                    <option value="cancel">Cancel</option>
                </select>

                <textarea name="reason"
                          class="form-control mb-3"
                          placeholder="Reason"
                          required></textarea>

                <button class="btn btn-primary w-100">
                    Submit Request
                </button>
            </form>
        </div>
    </div>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function toggleUpi(method) {
            document.getElementById('upiBox').style.display =
                method === 'upi' ? 'block' : 'none';
        }
    </script>
    </div>
@endsection
