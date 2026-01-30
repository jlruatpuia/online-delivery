@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')

    <section class="content-header">
        <h1>Delivery Overview</h1>
    </section>

    <!-- SUMMARY CARDS -->
    <section class="content">
        <div class="row">

            <div class="col-lg-2 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $stats['total'] }}</h3>
                        <p>Total</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $stats['delivered'] }}</h3>
                        <p>Delivered</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $stats['pending'] }}</h3>
                        <p>Pending</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $stats['cancelled'] }}</h3>
                        <p>Cancelled</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-secondary">
                    <div class="inner">
                        <h3>{{ $stats['rescheduled'] }}</h3>
                        <p>Rescheduled</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-6">
                <div class="small-box bg-dark">
                    <div class="inner">
                        <h3>₹ {{ number_format($stats['total_amount'], 2) }}</h3>
                        <p>Total Amount</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- DELIVERY BOY SUMMARY -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Delivery Boy Summary</h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Delivered</th>
                        <th>Pending</th>
                        <th>Cancelled</th>
                        <th>Rescheduled</th>
                        <th>Total Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deliveryBoys as $boy)
                        <tr>
                            <td>{{ $boy->name }}</td>
                            <td>{{ $boy->total_deliveries }}</td>
                            <td class="text-success">{{ $boy->delivered_count }}</td>
                            <td class="text-warning">{{ $boy->pending_count }}</td>
                            <td class="text-danger">{{ $boy->cancelled_count }}</td>
                            <td class="text-secondary">{{ $boy->rescheduled_count }}</td>
                            <td>₹ {{ number_format($boy->total_amount ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- LATEST DELIVERIES -->
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Latest Deliveries</h3>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Delivery Boy</th>
                        <th>Status</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->invoice_no }}</td>
                            <td>{{ optional($delivery->deliveryBoy)->name ?? '-' }}</td>
                            <td>
                        <span class="badge badge-info">
                            {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                        </span>
                            </td>
                            <td>₹ {{ number_format($delivery->amount, 2) }}</td>
                            <td>{{ $delivery->sales_date }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
