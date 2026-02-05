@extends('mobile.layout')

@section('title', 'Settlement')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">Settlement Submission</div>
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3">
                <div class="col-6">
                    <input type="date"
                           name="settlement_date"
                           value="{{ $settlement_date ?? \Carbon\Carbon::today() }}"
                           class="form-control"
                           required>
                </div>

                <div class="col-6">
                    <button class="btn btn-outline-primary w-100">
                        Calculate
                    </button>
                </div>

            </form>
            <hr/>
            @if($settlement_date)
                <div class="card shadow-sm mb-3">
                    <div class="card-body text-center">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="text-muted">Prepaid</div>
                                <span class="text-primary fw-semibold">₹ {{ number_format($totalPrepaid, 2) }}</span>
                            </div>
                            <div>
                                <div class="text-muted">Cash</div>
                                <span class="text-primary fw-semibold">₹ {{ number_format($totalCash, 2) }}</span>
                            </div>
                            <div>
                                <div class="text-muted">UPI</div>
                                <span class="text-primary fw-semibold">₹ {{ number_format($totalUpi, 2) }}</span>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="text-muted fw-semibold">Total Amount</span>
                            <span class="text-muted fw-semibold">₹ {{ number_format($totalAmount, 2) }}</span>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <div class="text-muted">Net Amount (Cash + Upi)</div>
                        <h3 class="text-success">
                            ₹ {{ number_format($netPayable, 2) }}
                        </h3>
                    </div>
                </div>

                <form method="POST" action="{{ route('mobile.settlement.store') }}">
                    @csrf
                    <input type="hidden" name="settlement_date" value="{{ $settlement_date }}">
{{--                    <input type="hidden" name="total_cash" value="{{ $totalCash }}">--}}
{{--                    <input type="hidden" name="total_upi" value="{{ $totalUpi }}">--}}
{{--                    <input type="hidden" name="amount" value="{{ $netPayable }}">--}}

                    <button class="btn btn-success w-100"
                        {{ $alreadySubmitted ? 'disabled' : '' }}>
                        {{ $alreadySubmitted
                            ? 'Settlement Already Submitted'
                            : 'Submit Settlement'
                        }}
                    </button>
                </form>
            @endif
        </div>
    </div>


    <div class="card mt-3 mb-2">
        <div class="card-header">Recent Settlements</div>
        @forelse($previousSettlements as $settlement)
        <div class="card-body d-flex justify-content-between">
            <div>
                <div>
                    {{ \Carbon\Carbon::parse($settlement->$settlement_date)->format('d-M-Y') }}
                </div>
                <small class="text-muted">
                    {{ ucfirst($settlement->status) }}
                </small>
            </div>
            <strong>
                ₹ {{ number_format($settlement->total_amount, 2) }}
            </strong>
        </div>
        @empty
            <div class="card-body">
                <div class="text-muted text-center">
                    No settlements yet
                </div>
            </div>
        @endforelse
    </div>


@endsection
