@extends('mobile.layout')

@section('title', 'My Profile')

@section('content')
<div class="mt-5 pt-4">
    <h6 class="fw-bold mb-3">My Profile</h6>

    <!-- BASIC INFO -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <div class="mb-2">
                <strong>Name:</strong> {{ auth()->user()->name }}
            </div>
            <div>
                <strong>Role:</strong> Delivery Boy
            </div>
        </div>
    </div>

    <!-- CHANGE USERNAME -->
    <div class="card shadow-sm mb-3">
        <form method="POST" action="{{ route('mobile.profile.username') }}">
            @csrf

            <h6 class="card-header">Change Username</h6>
            <div class="card-body">
                <input type="text"
                       name="username"
                       value="{{ auth()->user()->username }}"
                       class="form-control"
                       required>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary w-100">
                    Update Username
                </button>
            </div>
        </form>
    </div>

    <!-- CHANGE PASSWORD -->
    <div class="card shadow-sm mb-3">
        <form method="POST" action="{{ route('mobile.profile.password') }}">
            @csrf
            <h6 class="card-header">Change Password</h6>
            <div class="card-body">
                <div class="mb-2">
                    <label class="form-label">Current Password</label>
                    <input type="password"
                           name="current_password"
                           class="form-control"
                           required>
                </div>
                <div class="mb-2">
                    <label class="form-label">New Password</label>
                    <input type="password"
                           name="password"
                           class="form-control"
                           required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password"
                           name="password_confirmation"
                           class="form-control"
                           required>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success w-100">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- LOGOUT -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-danger w-100">
            Logout
        </button>
    </form>
</div>
@endsection
