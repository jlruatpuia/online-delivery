@extends('mobile.layout')

@section('title', 'Delivery Details')

@section('content')

    <!-- INVOICE & AMOUNT -->
    <div class="card shadow-sm mb-3">
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
        <div class="card-body">
            <h6 class="fw-bold mb-2">Customer</h6>

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

            <!-- ACTION BUTTONS -->
            <div class="d-grid gap-2">
                <a href="tel:{{ $delivery->customer->phone_no }}"
                   class="btn btn-outline-primary">
                    📞 Call Customer
                </a>

                <a href="https://www.google.com/maps?q={{ $delivery->customer->map_location }}"
                   target="_blank"
                   class="btn btn-primary">
                    📍 Navigate
                </a>
            </div>
        </div>
    </div>

    <!-- DELIVERY STATUS -->
    <div class="card shadow-sm">
        <div class="card-body text-center">
        <span class="badge bg-info">
            {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
        </span>
        </div>
    </div>
    <!-- ACTIONS -->
    @if(in_array($delivery->status, ['pending','assigned']))
        <div class="card shadow-sm">
            <div class="card-body">

                @if($delivery->payment_type === 'prepaid')
                    <!-- PREPAID CONFIRM -->
                    <form method="POST"
                          action="{{ route('mobile.delivery.confirm.prepaid', $delivery) }}">
                        @csrf
                        <button class="btn btn-success w-100">
                            ✅ Confirm Delivery
                        </button>
                    </form>
                @else
                    <!-- COD PAYMENT -->
                    <form method="POST"
                          action="{{ route('mobile.delivery.collect.cod', $delivery) }}">
                        @csrf

                        <label class="fw-bold mb-2">Payment Method</label>

                        <select name="payment_method"
                                class="form-select mb-2"
                                onchange="toggleUpi(this.value)"
                                required>
                            <option value="">Select</option>
                            <option value="cash">Cash</option>
                            <option value="upi">UPI</option>
                        </select>

                        <!-- UPI QR + REF -->
                        <div id="upiBox" style="display:none">
                            <div class="text-center mb-2">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=upi://pay?pa=merchant@upi&am={{ $delivery->amount }}"
                                     class="img-fluid">
                            </div>

                            <input name="upi_ref_no"
                                   class="form-control mb-2"
                                   placeholder="UPI Reference No">
                        </div>

                        <button class="btn btn-success w-100">
                            💰 Submit Payment
                        </button>
                    </form>
                @endif

                <hr>

                <!-- RESCHEDULE / CANCEL -->
                <button class="btn btn-outline-warning w-100 mb-2"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#requestCanvas">
                    🔄 Reschedule / Cancel
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            This delivery is already {{ ucfirst($delivery->status) }}.
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
        function toggleUpi(val){
            document.getElementById('upiBox').style.display =
                val === 'upi' ? 'block' : 'none';
        }
    </script>
@endsection
