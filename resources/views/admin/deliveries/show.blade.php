@extends('admin.layout')
@section('title', 'Delivery Detail')
@section('content')

    @php
    $status = $delivery->status;

    $color = null;
    switch($status) {
        case "pending":
            $color = 'warning';
            break;
        case "delivered":
            $color = 'success';
            break;
        case "cancelled":
            $color = 'danger';
            break;
        case 'reschedule_requested':
            $color = 'info';
            break;
        case 'cancel_requested':
            $color = 'info';
            break;
    }
    @endphp

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
                            <li class="breadcrumb-item">Deliveries</li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Delivery Detail</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="card border border-{{ $color }}">
                <h5 class="card-header">
                    Delivery Detail
                </h5>
                <div class="card-body p-3">
                    <div class="row mb-2">
                        <div class="col-2"><strong>Invoice No:</strong></div>
                        <div class="col-10"> {{ $delivery->invoice_no }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Delivery Date:</strong></div>
                        <div class="col-10"> {{ $delivery->delivery_date->format('d M Y') }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Delivery Boy:</strong></div>
                        <div class="col-10"> {{ $delivery->deliveryBoy->name }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Amount:</strong></div>
                        <div class="col-10"> ₹ {{ number_format($delivery->amount, 2) }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Payment Type:</strong></div>
                        <div class="col-10"> {{ strtoupper($delivery->payment_type) }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Status:</strong></div>
                        <div class="col-10"> {{ ucfirst(str_replace('_', ' ', ucwords($status, '_'))) }} </div>
                    </div>
                    @if($delivery->status == 'delivered')
                        <div class="row mb-2">
                            <div class="col-2"><strong>Payment Method:</strong></div>
                            <div class="col-10"> {{ strtoupper($delivery->payment->payment_method) }} </div>
                        </div>
                    @endif
                    <hr>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Customer Name:</strong></div>
                        <div class="col-10"> {{ ucfirst($delivery->customer->name) }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Address:</strong></div>
                        <div class="col-10"> {{ ucfirst($delivery->customer->address) }} </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-2"><strong>Phone No:</strong></div>
                        <div class="col-10"> {{ ucfirst($delivery->customer->phone_no) }} </div>
                    </div>

                    @if($status === 'reschedule_requested')
                        <hr/>
                        <div class="row mb-2  d-flex align-items-center">
                            <div class="col-2"><strong>Reschedule Date:</strong></div>
                            <div class="col-10"> {{ $delivery->rescheduled_at->format('d M Y') }} </div>
                        </div>
                    @endif
                </div>
                @if($status === 'reschedule_requested' || $status === 'cancel_requested')
                <div class="card-footer">
                    <form method="post" action="{{ route('admin.deliveries.approve', $delivery) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Approve</button>
                    </form>
                </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Reject Modal -->
{{--    <div class="modal fade" id="rejectModal">--}}
{{--        <div class="modal-dialog">--}}
{{--            <form method="POST"--}}
{{--                  action="{{ route('admin.settlements.reject', $settlement) }}"--}}
{{--                  class="modal-content">--}}
{{--                @csrf--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">Reject Settlement</h5>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                <textarea name="reason"--}}
{{--                          class="form-control"--}}
{{--                          placeholder="Reason for rejection"--}}
{{--                          required></textarea>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button class="btn btn-secondary"--}}
{{--                            data-bs-dismiss="modal">--}}
{{--                        Cancel--}}
{{--                    </button>--}}
{{--                    <button class="btn btn-danger">--}}
{{--                        Reject--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </form>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
