@extends('mobile.layout')

@section('content')

    <h6>Settlement</h6>

    <div class="card text-center">
        <div class="card-body">
            <h3>₹ {{ number_format($total,2) }}</h3>
            <p>Total Verified Amount</p>
        </div>
    </div>

@endsection
