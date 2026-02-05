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

    $hasMap = !empty($delivery['has_map_location']);
    $customer = $delivery['customer'] ?? null;
@endphp

<div class="card mb-3"
     data-call="tel:{{ $delivery['customer']['phone'] ?? '' }}"
     data-navigation="{{ $delivery['navigation_url'] ?? '' }}">

    <!-- CARD (NOT CLICKABLE) -->
    <div class="card swipe-card shadow-sm">
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
                    @if($delivery['status'] == 'delivered')
                    <div class="small text-muted mb-1">
                        <i class="bi bi-clock"></i>
                        {{ $delivery['delivered_at']->format('d M Y, h:i A') }}
                    </div>
                    @endif
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
            <div class="btn-group w-100" role="group" aria-label="Basic example">
                <a class="btn btn-outline-secondary" href="tel:{{ $delivery['customer']['phone'] }}">
                    📞
                </a>
                @if($hasMap)
                <a href="{{ $delivery['navigation_url'] }}" class="btn btn-outline-info"
                   target="_blank">
                    📍
                </a>
                @else
                    <span class="btn btn-outline-success disabled">📍</span>
                @endif

                <a class="btn btn-outline-primary" href="{{ route('mobile.delivery.show', $delivery['id']) }}">
                    📦
                </a>
            </div>
{{--            <a href="{{ route('mobile.delivery.show', $delivery['id']) }}"--}}
{{--               class="btn btn-primary-subtle w-100"--}}
{{--               >--}}
{{--                --}}{{--                    📦 View Details--}}
{{--                <i class="bi bi-eye fs-3"></i>--}}
{{--            </a>--}}
        </div>
    </div>
</div>
