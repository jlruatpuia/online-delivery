@extends('admin.layout')
@section('title', 'Settlement Detail')
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
                            <li class="breadcrumb-item">Settlements</li>
                            <li class="breadcrumb-item active" aria-current="page">Detail</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Settlements</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="card">

                <h5 class="card-header">Settlement Detail</h5>
                <div class="card-body">
                    <p><strong>Delivery Boy:</strong> {{ $settlement->deliveryBoy->name }}</p>
                    <p><strong>Date:</strong> {{ $settlement->settlement_date->format('d M Y') }}</p>

                    <hr>

                    <p>💵 Cash: ₹ {{ number_format($settlement->cash_amount, 2) }}</p>
                    <p>📲 UPI: ₹ {{ number_format($settlement->upi_amount, 2) }}</p>

                    <h5>💰 Total: ₹ {{ number_format($settlement->total_amount, 2) }}</h5>
                </div>
                <div class="card-footer">
                    @if($settlement->status === 'submitted')
                        <div class="d-flex gap-2">
                            <form method="POST"
                                  action="{{ route('admin.settlements.approve', $settlement) }}">
                                @csrf
                                <button class="btn btn-success">
                                    Approve
                                </button>
                            </form>

                            <button class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#rejectModal">
                                Reject
                            </button>
                        </div>
                    @else
                        <div class="alert alert-success">
                            Settlement already {{ $settlement->status }}.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal">
        <div class="modal-dialog">
            <form method="POST"
                  action="{{ route('admin.settlements.reject', $settlement) }}"
                  class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Settlement</h5>
                </div>
                <div class="modal-body">
                <textarea name="reason"
                          class="form-control"
                          placeholder="Reason for rejection"
                          required></textarea>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn btn-danger">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
