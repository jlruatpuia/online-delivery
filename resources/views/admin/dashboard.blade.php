@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
    <!-- Content Header (Page header) -->
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__heading-container">
            <div class="page__heading">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="#">
                                <i class="fa fa-tachometer-alt"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>


        <div class="container-fluid page__container">
            <div class="row card-group-row">
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center bg-primary-subtle">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Total Deliveries</div>
                            <div class="text-amount">{{ $stats['total'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">box</i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Delivered</div>
                            <div class="text-amount">{{ $stats['delivered'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">hand_package</i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Pending</div>
                            <div class="text-amount">{{ $stats['pending'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">pending_actions</i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Cancelled</div>
                            <div class="text-amount">{{ $stats['cancelled'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">enterprise_off</i></div>
                    </div>
                </div>
            </div>
            <div class="row card-group-row">
                <div class="col-lg-4 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Cancelled</div>
                            <div class="text-amount">{{ $stats['cancelled'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">enterprise_off</i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Rescheduled</div>
                            <div class="text-amount">{{ $stats['rescheduled'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">edit_calendar</i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">Amount</div>
                            <div class="text-amount">₹ {{ number_format($stats['total_amount'], 2) }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">money_range</i></div>
                    </div>
                </div>
            </div>

            <div class="card">
                <h5 class="card-header">Delivery Boy Summary</h5>
                <div class="card-body">
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
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Delivery Status Chart -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Delivery Status</h6>
                                    <canvas id="deliveryStatusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Boy Performance Chart -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Delivery Boy Performance</h6>
                                    <canvas id="deliveryBoyChart"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Payment Type (Cash vs UPI)</h6>
                                    <canvas id="paymentTypeChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const deliveryStatusData = @json($deliveryStatus);

        new Chart(
            document.getElementById('deliveryStatusChart'),
            {
                type: 'doughnut',
                data: {
                    labels: Object.keys(deliveryStatusData),
                    datasets: [{
                        data: Object.values(deliveryStatusData),
                    }]
                }
            }
        );

        const performanceData = @json($deliveryBoyPerformance);

        new Chart(
            document.getElementById('deliveryBoyChart'),
            {
                type: 'bar',
                data: {
                    labels: performanceData.map(x => x.name),
                    datasets: [
                        {
                            label: 'Total Deliveries',
                            data: performanceData.map(x => x.total)
                        },
                        {
                            label: 'Delivered',
                            data: performanceData.map(x => x.delivered)
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            }
        );

        const paymentData = @json($paymentSummary);

        new Chart(
            document.getElementById('paymentTypeChart'),
            {
                type: 'doughnut',
                data: {
                    labels: Object.keys(paymentData).map(x =>
                        x.toUpperCase()
                    ),
                    datasets: [{
                        data: Object.values(paymentData)
                    }]
                }
            }
        );
    </script>

@endsection
