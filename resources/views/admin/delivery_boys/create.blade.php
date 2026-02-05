@extends('admin.layout')

@section('title', 'Create Delivery Boy')

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
                            <li class="breadcrumb-item"><a href="{{ route('admin.delivery_boys.index') }}">Delivery Boys</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Add New</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Delivery Boys</h1>
                </div>
                <a href="{{ route('admin.delivery_boys.index') }}" class="btn btn-success ml-3">Back</a>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="card card-primary">
                <h5 class="card-header">Add New Delivery Boy</h5>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.delivery_boys.store') }}">
                        @csrf

                        <div class="form-group">
                            <label>Name</label>
                            <input type="text"
                                   name="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}">

                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text"
                                   name="username"
                                   class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username') }}">

                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror">

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control">
                        </div>

                        <button class="btn btn-success">
                            <i class="fas fa-save"></i> Create
                        </button>

                        <a href="{{ route('admin.delivery_boys.index') }}"
                           class="btn btn-secondary">
                            Back
                        </a>

                    </form>

                </div>
            </div>
        </div>


    </div>

@endsection
