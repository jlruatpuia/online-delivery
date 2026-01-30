@extends('mobile.layout')

@section('content')

    <ul class="nav nav-tabs mb-3">
        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#pending">Pending</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#completed">Completed</a></li>
        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#cancelled">Cancelled</a></li>
    </ul>

    <div class="tab-content">
        @foreach(['pending','completed','cancelled'] as $tab)
            <div class="tab-pane fade {{ $tab=='pending'?'show active':'' }}" id="{{ $tab }}">
                @foreach($$tab as $delivery)
                    <a href="{{ route('mobile.delivery.show', $delivery) }}"
                       class="card mb-2 p-2 text-decoration-none text-dark">
                        <strong>#{{ $delivery->invoice_no }}</strong>
                        <div class="small text-muted">₹ {{ $delivery->amount }}</div>
                    </a>
                @endforeach
            </div>
        @endforeach
    </div>

@endsection
