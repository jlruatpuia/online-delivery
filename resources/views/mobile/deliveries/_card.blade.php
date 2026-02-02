@php
    $statusConfig = [
        'pending' => ['warning', 'Pending'],
        'assigned' => ['primary', 'Assigned'],
        'delivered' => ['success', 'Delivered'],
        'cancelled' => ['danger', 'Cancelled'],
        'cancel_requested' => ['danger', 'Cancel Requested'],
        'reschedule_requested' => ['secondary', 'Reschedule Requested'],
    ];

    [$badgeColor, $statusLabel] =
        $statusConfig[$delivery['status']]
        ?? ['dark', ucfirst($delivery['status'])];

    $isUrgent =
        in_array($delivery['status'], ['pending','assigned']) &&
        $delivery['created_at']->diffInHours(now()) >= 2;

    $hasMap = !empty($delivery['has_map_location']);
    $customer = $delivery['customer'] ?? null;
@endphp

<div class="swipe-container mb-3"
     data-call="tel:{{ $delivery['customer']['phone'] ?? '' }}"
     data-navigation="{{ $delivery['navigation_url'] ?? '' }}">


    <!-- LEFT ACTION -->
    <div class="swipe-action swipe-left">
        📞 Call
    </div>

    <!-- RIGHT ACTION -->
    <div class="swipe-action swipe-right">
        📍 Navigate
    </div>

    <!-- CARD (NOT CLICKABLE) -->
    <div class="card swipe-card shadow-sm
                {{ $isUrgent ? 'border-danger' : '' }}"
         oncontextmenu="return false"
         ontouchstart="startPress(event,
            '{{ route('mobile.delivery.show', $delivery['id']) }}',
            '{{ $delivery['customer']['phone'] }}',
            '{{ $delivery['navigation_url'] }}'
         )"
         ontouchend="cancelPress()"
         onmousedown="startPress(event,
            '{{ route('mobile.delivery.show', $delivery['id']) }}',
            '{{ $delivery['customer']['phone'] }}',
            '{{ $delivery['navigation_url'] }}'
         )"
         onmouseup="cancelPress()"
    >
        <div class="card-header d-flex justify-content-between align-items-start">
            <div>
                <strong>#{{ $delivery['invoice_no'] }}</strong>

                {{-- PAYMENT TYPE --}}
                @if($delivery['payment_type'] === 'prepaid')
                    <span class="ms-1">💳</span>
                @else
                    <span class="ms-1">💵</span>
                @endif
            </div>

            {{-- STATUS --}}
            <span class="badge bg-{{ $badgeColor }}">
                {{ $statusLabel }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    {{-- URGENT --}}
                    @if($isUrgent)
                        <span class="badge bg-danger mb-2">🔔 Urgent</span>
                    @endif

                    {{-- AMOUNT --}}
                    <div class="fw-semibold text-success">
                        ₹ {{ number_format($delivery['amount'], 2) }}
                    </div>

                    {{-- CUSTOMER --}}
                    <div class="small text-muted">
                        <i class="bi bi-person"></i>
                        {{ $delivery['customer']['name'] }}
                    </div>
                    <div class="small text-muted">
                        <i class="bi bi-geo-alt"></i>
                        {{ $delivery['customer']['address'] }}
                    </div>
                    {{-- DATE / TIME --}}
                    <div class="small text-muted mb-1">
                        <i class="bi bi-clock"></i>
                        {{ $delivery['created_at']->format('d M Y, h:i A') }}
                    </div>
                </div>
                <div class="col-4">
                    {{-- MINI MAP --}}
                    @if($hasMap)
                        <a href="{{ $delivery['navigation_url'] }}"
                           target="_blank" class="d-block mt-2">
                            <img src="{{ $delivery['staticMapUrl'] }}"
                                 class="img-fluid rounded border"
                                 alt="Map Preview"
                                 style="max-height:220px; object-fit: cover"
                            >
                        </a>
                    @endif
                </div>
            </div>



        </div>
        <div class="card-footer">
            {{-- DETAILS BUTTON --}}

            <a href="{{ route('mobile.delivery.show', $delivery['id']) }}"
               class="btn btn-primary-subtle w-100"
               >
                {{--                    📦 View Details--}}
                <i class="bi bi-eye fs-3"></i>
            </a>
        </div>
    </div>
</div>
