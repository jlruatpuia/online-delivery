@extends('admin.layout')
@section('title', 'Performance')
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
                            <li class="breadcrumb-item active" aria-current="page">Performance</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Delivery Boys</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <form method="get">
                <div class="card card-form d-flex flex-column flex-sm-row">
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col-sm-auto">
                                <div class="form-group" style="width: 200px;">
                                    <label for="dateRange">Select Date Range</label>
                                    <input type="text" id="dateRange"
                                           name="date_range"
                                           class="form-control" placeholder="Select Date Range"
                                           value="{{ request('date_range') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">refresh</i>
                    </button>

                </div>
            </form>
            <div class="card">
                <h5 class="card-header">Delivery Boys Performance</h5>
                <div class="card-body">
                    <table id="performanceTable"
                           class="table table-bordered table-hover align-middle">
                        <thead>
                        <tr>
                            <th>Delivery Boy</th>
                            <th>Total</th>
                            <th>Delivered</th>
                            <th>Cancelled</th>
                            <th>Rescheduled</th>
                            <th>Success %</th>
                            <th>Total Amount</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($performance as $row)
                            <tr data-href="{{ route('admin.delivery_boys.performance.show', $row['id']) }}"
                                style="cursor:pointer">
                                <td>{{ $row['name'] }}</td>
                                <td>{{ $row['total'] }}</td>
                                <td class="text-success">{{ $row['delivered'] }}</td>
                                <td class="text-danger">{{ $row['cancelled'] }}</td>
                                <td class="text-warning">{{ $row['rescheduled'] }}</td>
                                <td>{{ $row['success_rate'] }}%</td>
                                <td class="fw-bold">₹ {{ number_format($row['total_amount'], 2) }}</td>
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
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d-m-Y",
            maxDate: "today",
            defaultDate: [
                "{{ now()->startOfMonth()->toDateString() }}",
                "{{ now()->toDateString() }}"
            ]
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#performanceTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                lengthChange: false,

            });
        });
    </script>
    <script>
        document.querySelectorAll('#performanceTable tbody tr')
            .forEach(row => {
                row.addEventListener('click', () => {
                    window.location = row.dataset.href;
                });
            });
    </script>
@endsection
