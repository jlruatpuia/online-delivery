@extends('admin.layout')

@section('title', 'Create Delivery Boy')

@section('content')

    <section class="content-header">
        <h1>Create Delivery Boy</h1>
    </section>

    <section class="content">
        <div class="card card-primary">
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
    </section>

@endsection
