@extends('admin.layout')
@section('title', 'Profile')
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
                            <li class="breadcrumb-item active" aria-current="page">Profile</li>
                        </ol>
                    </nav>
                    <h1 class="mt-2">{{ auth()->user()->name }} – Profile</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <form method="POST"
                  action="{{ route('admin.profile.update') }}">
                @csrf
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p><strong class="headings-color">Basic Information</strong></p>
                            <p class="text-muted">Edit your account details and settings.</p>
                        </div>
                        <div class="col-lg-8 card-form__body card-body">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" name="name" type="text" class="form-control" placeholder="Name" value="{{ old('name', $user->name) }}">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input id="username" name="username" type="text" class="form-control" placeholder="Username" value="{{ old('username', $user->username) }}">
                            </div>
                            <button class="btn btn-primary">
                                Save Profile
                            </button>
                        </div>

                    </div>
                </div>
            </form>
            <form method="POST"
                  action="{{ route('admin.profile.password') }}">
                @csrf
                <div class="card card-form">
                    <div class="row no-gutters">
                        <div class="col-lg-4 card-body">
                            <p><strong class="headings-color">Update Your Password</strong></p>
                            <p class="text-muted">Change your password.</p>
                        </div>
                        <div class="col-lg-8 card-form__body card-body">
                            <div class="form-group">
                                <label for="opass">Old Password</label>
                                <input style="width: 270px;" id="opass" name="current_password" type="password" class="form-control" placeholder="Current Password" required>
                                @error('current_password')
                                <small class="text-danger">
                                    {{ $message }}
                                </small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="npass">New Password</label>
                                <input style="width: 270px;" id="npass" name="password" type="password" class="form-control" placeholder="New Password" required>
                            </div>
                            <div class="form-group">
                                <label for="cpass">Confirm Password</label>
                                <input style="width: 270px;" id="cpass" name="password_confirmation" type="password" class="form-control" placeholder="Confirm Password" required>
                            </div>
                            <button class="btn btn-warning">
                                Change Password
                            </button>
                        </div>

                    </div>
                </div>
            </form>
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
