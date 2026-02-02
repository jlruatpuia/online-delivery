@extends('mobile.layout')
@section('title', 'Login | Rose Online Delivery')
@section('content')
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-sm w-100" style="max-width: 360px;">
            <div class="card-header">Delivery Login</div>
            <form method="POST" action="{{ route('mobile.login.submit') }}">
                @csrf
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input type="text"
                               name="username"
                               id="username"
                               value="{{ old('username') }}"
                               class="form-control"
                               placeholder="Username"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">Password</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="Password"
                               required>
                    </div>

                    @error('username')
                    <div class="text-danger small mb-2">
                        {{ $message }}
                    </div>
                    @enderror
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary w-100">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
{{--    <div class="container vh-100 d-flex align-items-center justify-content-center">--}}

{{--        <div class="card shadow w-100" style="max-width: 360px;">--}}
{{--            <div class="card-body">--}}

{{--                <h5 class="text-center mb-3">Delivery Login</h5>--}}

{{--                --}}

{{--            </div>--}}
{{--        </div>--}}

{{--    </div>--}}
@endsection
