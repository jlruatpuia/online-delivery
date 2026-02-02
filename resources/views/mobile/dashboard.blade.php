@extends('mobile.layout')

@section('title', 'Dashboard')

@section('content')
    <!-- WELCOME -->
    <div class="mb-3">
        <h5 class="fw-bold mb-0">
            Welcome, {{ auth()->user()->name }} 👋
        </h5>
        <small class="text-muted">
            Have a safe delivery today
        </small>
    </div>
    <div id="dashboard-content">
    <!-- DELIVERY COUNTS -->
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h3 class="text-warning">{{ $pendingCount }}</h3>
                        <div class="small text-muted">Pending</div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <h3 class="text-success">{{ $completedCount }}</h3>
                        <div class="small text-muted">Completed</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAYMENT SUMMARY -->
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold">
                Collections
            </div>
            <div class="card-body">
{{--                <h6 class="fw-bold mb-3">Collections</h6>--}}

                <div class="d-flex justify-content-between mb-2">
                    <span>Prepaid</span>
                    <strong>₹ {{ number_format($totalPrepaid, 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Cash</span>
                    <strong>₹ {{ number_format($totalCash, 2) }}</strong>
                </div>

                <div class="d-flex justify-content-between">
                    <span>UPI</span>
                    <strong>₹ {{ number_format($totalUpi, 2) }}</strong>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-between">
                <span class="fw-semibold">NET TOTAL</span>
                <span class="fw-semibold">₹ {{ number_format($netTotal, 2) }}</span>
            </div>
        </div>

        <!-- RECENT ACTIVITIES -->
        <div class="card shadow-sm">
            <div class="card-header fw-semibold">Recent Deliveries</div>
            <div class="card-body">
                @forelse($recentDeliveries as $delivery)
                    <div class="d-flex justify-content-between border-bottom py-2">
                        <div>
                            <div class="fw-semibold">
                                Invoice #{{ $delivery->invoice_no }}
                            </div>
                            <small class="text-muted">
                                {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                            </small>
                        </div>

                        <div class="text-end">
                            <div>₹ {{ number_format($delivery->amount, 2) }}</div>
                            <small class="text-muted">
                                {{ $delivery->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted">
                        No recent activity
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        setInterval(() => {
            fetch("{{ route('mobile.dashboard') }}", {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.text())
                .then(html => {
                    document.getElementById('dashboard-content').innerHTML =
                        new DOMParser()
                            .parseFromString(html, 'text/html')
                            .getElementById('dashboard-content')
                            .innerHTML;
                });
        }, 30000); // refresh every 30 seconds
    </script>

@endsection
