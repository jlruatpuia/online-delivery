@extends('admin.layout')
@section('title', 'Settlements')
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
                            <li class="breadcrumb-item active" aria-current="page">Settlements</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Settlements</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <form method="get">
                <div class="card card-form d-flex flex-column flex-sm-row">
                    @php
                        $defaultFrom = now()->startOfMonth()->toDateString();
                        $defaultTo   = now()->toDateString();
                    @endphp
                    <div class="card-form__body card-body-form-group flex">
                        <div class="row">
                            <div class="col-sm-auto">
                                <div class="form-group" style="width: 200px;">
                                    <label for="dateRange">Settlement Date</label>
                                    <input type="text" id="dateRange" name="date_range"
                                           class="form-control" placeholder="Select Date Range"
                                           value="{{ request('date_range', $defaultFrom.' to '.$defaultTo) }}">
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-group">
                                    <label for="delivery_boy">Delivery Boy</label>
                                    <select id="delivery_boy" name="delivery_boy" class="custom-select">
                                        <option value="">-- ALL --</option>
                                        @foreach($delivery_boys as $b)
                                            <option value="{{ $b->id }}"
                                               {{ request('delivery_boy') == $b->id ? 'selected' : '' }}
                                            >{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" name="status" class="custom-select">
                                        <option value="">-- ALL --</option>
                                        <option value="submitted"
                                            {{ request('status') == 'submitted' ? 'selected' : '' }}
                                        >Submitted</option>
                                        <option value="approved"
                                            {{ request('status') == 'approved' ? 'selected' : '' }}
                                        >Approved</option>
                                        <option value="rejected"
                                            {{ request('status') == 'rejected' ? 'selected' : '' }}
                                        >Rejected</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn bg-white border-left border-top border-top-sm-0 rounded-top-0 rounded-top-sm rounded-left-sm-0"><i class="material-icons text-primary icon-20pt">refresh</i>
                    </button>

                </div>
            </form>

            <div class="card">
                <h5 class="card-header">Submitted Settlements</h5>
                <div class="card-body">
                    <table id="settlementTable" class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Delivery Boy</th>
                            <th>Date</th>
                            <th>Cash</th>
                            <th>UPI</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($settlements as $s)
                            <tr>
                                <td>{{ $s->deliveryBoy->name }}</td>
                                <td>{{ $s->settlement_date->format('d M Y') }}</td>
                                <td>₹ {{ number_format($s->cash_amount, 2) }}</td>
                                <td>₹ {{ number_format($s->upi_amount, 2) }}</td>
                                <td><strong>₹ {{ number_format($s->total_amount, 2) }}</strong></td>
                                <td>
                                    @if($s->status == 'submitted')
                                        <span class="badge badge-primary">Submitted</span>
                                    @elseif($s->status == 'approved')
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.settlements.show', $s) }}"
                                       class="btn btn-sm btn-primary">
                                        View
                                    </a>
                                </td>
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
    <!-- Flatpickr -->
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

            $('#settlementTable').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                searching: true,
                info: true,
                autoWidth: false,

                columnDefs: [
                    { orderable: false, targets: 5 } // Action column
                ]
            });

        });
    </script>
@endsection
