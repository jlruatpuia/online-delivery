@extends('admin.layout')
@section('title', 'Performance Detail')
@section('content')
    <div class="mdk-drawer-layout__content page">

        <div class="container-fluid page__heading-container">
            <div class="page__heading d-flex align-items-center">
                <div class="flex">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="/admin/dashboard">
                                    <i class="fa fa-tachometer-alt"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">Delivery Boys</li>
                            <li class="breadcrumb-item">Performance</li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <h1 class="mt-2">{{ $user->name }} – Performance Detail</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="row card-group-row">
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center bg-primary-subtle">
                        <div class="flex">
                            <div class="card-header__title text-warning mb-2">Pending</div>
                            <div class="text-amount">{{ $statusCounts['pending'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">pending_actions</i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-success mb-2">Delivered</div>
                            <div class="text-amount">{{ $statusCounts['delivered'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">hand_package</i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-info mb-2">Rescheduled</div>
                            <div class="text-amount">{{ $statusCounts['rescheduled'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">pending_actions</i></div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-danger mb-2">Cancelled</div>
                            <div class="text-amount">{{ $statusCounts['cancelled'] }}</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">enterprise_off</i></div>
                    </div>
                </div>
            </div>
            <div class="row card-group-row">
                <div class="col-lg-4 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center bg-primary-subtle">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">💵 COD</div>
                            <div class="text-amount">₹ {{ number_format($codAmount, 2) }}</div>
                            <div class="text-stats text-primary">{{ $codCount }} orders</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">currency_exchange</i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">💳 Prepaid</div>
                            <div class="text-amount">₹ {{ number_format($prepaidAmount, 2) }}</div>
                            <div class="text-stats text-primary">{{ $prepaidCount }} orders</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">money_off</i></div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4 card-group-row__col">
                    <div class="card card-group-row__card card-body card-body-x-lg flex-row align-items-center">
                        <div class="flex">
                            <div class="card-header__title text-muted mb-2">💰 Total Amount</div>
                            <div class="text-amount">₹ {{ number_format($totalAmount, 2) }}</div>
                            <div class="text-stats text-primary">{{ $totalCount }} orders</div>
                        </div>
                        <div><i class="material-symbols-outlined icon-muted icon-40pt ml-3">score</i></div>
                    </div>
                </div>
            </div>
            <!-- 📦 Delivery Status Summary -->


            <div class="card">
                <h5 class="card-header">Period: {{ \Carbon\Carbon::parse($from)->format('d M Y') }} → {{ \Carbon\Carbon::parse($to)->format('d M Y') }}</h5>
                <div class="card-body">
                    <table id="performanceTable" class="table table-bordered align-middle">
                        <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($deliveries as $d)
                            <tr>
                                <td>{{ $d->invoice_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($d->delivery_date)->format('d-M-Y') }}</td>
                                <td>{{ ucfirst($d->status) }}</td>
                                <td>{{ strtoupper($d->payment_type ?? '-') }}</td>
                                <td>₹ {{ number_format($d->amount, 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#performanceTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                lengthChange: true,

            });
        });
    </script>
@endsection
