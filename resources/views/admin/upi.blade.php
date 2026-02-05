@extends('admin.layout')

@section('title', 'UPI Details')

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
                            <li class="breadcrumb-item active" aria-current="page">UPI Details</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Delivery Boys</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="card card-primary">
                <h5 class="card-header">UPI Details</h5>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.upi-update') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $upi->id }}">
                        <div class="form-group">
                            <label>UPI ID</label>
                            <input type="text"
                                   name="upi_id"
                                   class="form-control @error('upi_id') is-invalid @enderror"
                                   value="{{ $upi->upi_id }}">

                            @error('upi_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Payee Name</label>
                            <input type="text"
                                   name="payee_name"
                                   class="form-control @error('payee_name') is-invalid @enderror"
                                   value="{{ $upi->payee_name }}">

                            @error('payee_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button class="btn btn-success">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </form>

                </div>
            </div>
        </div>


    </div>

@endsection
