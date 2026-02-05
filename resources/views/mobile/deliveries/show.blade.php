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

        [$color, $label] = $statusMap[$delivery['status']]
            ?? ['dark', ucfirst($delivery['status'])];
        $hasMap = !empty($delivery['has_map_location']);
    @endphp
    <!-- INVOICE & AMOUNT -->
    <div class="card card-body shadow-sm mb-3 bg-{{ $color }}">
        <div class="d-flex justify-content-between">
            <div>
                <div class="text-muted small">Invoice No</div>
                <h6 class="mb-0">#{{ $delivery['invoice_no'] }}</h6>
                <span class="text-sm-end">{{ $label }}</span>
            </div>
            <div class="text-end">
                <div class="text-muted small">Amount</div>
                <h6 class="mb-0 text-success">
                    ₹ {{ number_format($delivery['amount'], 2) }}
                </h6>
            </div>
        </div>
    </div>

    <!-- CUSTOMER INFO -->
    <div class="card shadow-sm mb-3">
        <div class="card-header fw-semibold">Customer</div>
        <div class="card-body">
            <div class="mb-2">
                <i class="bi bi-person me-2"></i>
                {{ $delivery['customer']['name'] }}
            </div>

            <div class="mb-2">
                <i class="bi bi-telephone me-2"></i>
                <a href="tel:{{ $delivery['customer']['phone'] }}"
                   class="text-decoration-none">
                    {{ $delivery['customer']['phone'] }}
                </a>
            </div>

            <div class="mb-3">
                <i class="bi bi-geo-alt me-2"></i>
                {{ $delivery['customer']['address'] }}
            </div>


        </div>
        <div class="card-footer d-flex justify-content-between">
            <!-- ACTION BUTTONS -->
                <a href="tel:{{ $delivery['customer']['phone'] }}"
                   class="btn btn-outline-primary">
                    📞 Call Customer
                </a>

            @if($hasMap)
                <a href="{{ $delivery['navigation_url'] }}"
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
    @if(in_array($delivery['status'], ['pending','assigned']))
        {{-- ✅ PREPAID DELIVERY --}}

        <div class="card mt-3">

            <h6 class="card-header text-center">💰 Collect Payment</h6>
            <div class="card-body">
                @if($delivery['payment_type'] === 'prepaid')

                    <form method="POST" onclick="return confirm('Confirm Delivery?')"
                          action="{{ route('mobile.delivery.confirm.prepaid', $delivery['id']) }}">
                        @csrf

                        <button class="btn btn-success w-100 py-4 fs-3">
                            ✅ Confirm Delivery
                        </button>
                    </form>

                    {{-- 💰 CASH ON DELIVERY --}}
                @else


                    <!-- Payment Buttons -->
                    <div class="d-flex gap-2">
                        <button id="btnCash"
                                class="btn btn-outline-success w-50 py-3 fs-3">
                            💵 CASH
                        </button>

                        <button id="btnUpi"
                                class="btn btn-outline-danger w-50 py-3 fs-3">
                            📲 UPI
                        </button>
                    </div>

                    <!-- CASH CONFIRM -->
                    <form id="cashForm"
                          method="POST"
                          action="{{ route('mobile.delivery.collect.cod', $delivery['id']) }}"
                          class="d-none mt-3">
                        @csrf
                        <input type="hidden" name="payment_method" value="cash">

                        <button type="submit"
                                class="btn btn-success w-100">
                            ✔ Confirm Cash Received
                        </button>
                    </form>

                    <!-- UPI PANEL -->
                    <div id="upiPanel" class="d-none mt-3 text-center">

                        <img
                            src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data={{ urlencode($delivery['upi']) }}"
                            class="img-fluid mb-2"
                            alt="UPI QR Code">

                        <div class="small text-muted mb-2">
                            Amount: ₹{{ number_format($delivery['amount'], 2) }}
                        </div>

                        <!-- Optional Reference No -->
                        <form method="POST"
                              action="{{ route('mobile.delivery.collect.cod', $delivery['id']) }}">
                            @csrf
                            <input type="hidden" name="payment_method" value="upi">

                            <input type="text"
                                   name="reference_no"
                                   class="form-control mb-2"
                                   placeholder="UPI Reference No (optional)">

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                ✔ Confirm UPI Payment
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-content-between mt-3">
            <div class="btn-group w-100" role="group" aria-label="Basic example">
                <button class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#rescheduleModal">
                    🔄 Reschedule
                </button>
                <button class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#cancelModal">
                    ❌ Cancel Delivery
                </button>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            This delivery is already
            <strong>{{ ucfirst($delivery['status']) }}</strong>.
        </div>
    @endif
    <!-- 🔄 Reschedule Delivery Modal -->
    <div class="modal fade" id="rescheduleModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('mobile.delivery.request', $delivery['id']) }}">
                    @csrf

                    <div class="modal-header">
                        <h6 class="modal-title">🔄 Reschedule Delivery</h6>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" name="type" value="reschedule">
                        <div class="mb-3">
                            <label class="form-label">New Delivery Date</label>
                            <input type="date"
                                   name="reschedule_date"
                                   class="form-control"
                                   min="{{ now()->toDateString() }}"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea name="reason"
                                      class="form-control"
                                      rows="3"
                                      required></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit"
                                class="btn btn-warning">
                            Submit Reschedule
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <!-- ❌ Cancel Delivery Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form method="POST"
                      action="{{ route('mobile.delivery.request', $delivery['id']) }}"">
                    @csrf
                    <input type="hidden" name="type" value="cancel">
                    <div class="modal-header">
                        <h6 class="modal-title text-danger">❌ Cancel Delivery</h6>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="alert alert-warning small">
                            This action cannot be undone.
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cancellation Reason</label>
                            <textarea name="reason"
                                      class="form-control"
                                      rows="3"
                                      required></textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">
                            Back
                        </button>

                        <button type="submit"
                                class="btn btn-danger">
                            Confirm Cancel
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    <script src="https://unpkg.com/html5-qrcode"></script>

    <script>
        document.getElementById('btnCash')?.addEventListener('click', () => {
            if (!confirm('Confirm cash payment received?')) return;

            document.getElementById('cashForm').classList.remove('d-none');
            document.getElementById('upiPanel').classList.add('d-none');
        });

        document.getElementById('btnUpi')?.addEventListener('click', () => {
            document.getElementById('upiPanel').classList.remove('d-none');
            document.getElementById('cashForm').classList.add('d-none');
        });
    </script>
    @if(session('payment_success'))
        <script>
            // 🔔 Beep sound
            const beep = new Audio('/sounds/beep.wav');
            beep.play().catch(() => {});

            // 📳 Vibration (mobile only)
            if (navigator.vibrate) {
                navigator.vibrate([200, 100, 200]);
            }
        </script>
    @endif
@endsection
@section('script')

@endsection
