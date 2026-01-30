@extends('mobile.layout')

@section('title', 'Settlement')

@section('content')

    <h6 class="fw-bold mb-3">Settlement Submission</h6>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-6">
            <input type="date"
                   name="from_date"
                   value="{{ $from }}"
                   class="form-control"
                   required>
        </div>

        <div class="col-6">
            <input type="date"
                   name="to_date"
                   value="{{ $to }}"
                   class="form-control"
                   required>
        </div>

        <div class="col-12">
            <button class="btn btn-outline-primary w-100">
                Calculate
            </button>
        </div>
    </form>

    @if($from && $to)
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <div class="text-muted">Total Verified Amount</div>
                <h3 class="text-success">
                    ₹ {{ number_format($totalAmount, 2) }}
                </h3>
            </div>
        </div>

        <form method="POST" action="{{ route('mobile.settlement.store') }}">
            @csrf
            <input type="hidden" name="from_date" value="{{ $from }}">
            <input type="hidden" name="to_date" value="{{ $to }}">

            <button class="btn btn-success w-100"
                {{ $alreadySubmitted ? 'disabled' : '' }}>
                {{ $alreadySubmitted
                    ? 'Settlement Already Submitted'
                    : 'Submit Settlement'
                }}
            </button>
        </form>
    @endif

    <hr>

    <h6 class="fw-bold">Recent Settlements</h6>

    @forelse($previousSettlements as $settlement)
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <div>
                        {{ $settlement->from_date }}
                        → {{ $settlement->to_date }}
                    </div>
                    <small class="text-muted">
                        {{ ucfirst($settlement->status) }}
                    </small>
                </div>
                <strong>
                    ₹ {{ number_format($settlement->total_amount, 2) }}
                </strong>
            </div>
        </div>
    @empty
        <div class="text-muted text-center">
            No settlements yet
        </div>
    @endforelse

@endsection
