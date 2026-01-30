@extends('mobile.layout')

@section('content')

    <h6>My Profile</h6>

    <ul class="list-group">
        <li class="list-group-item">Name: {{ auth()->user()->name }}</li>
        <li class="list-group-item">Username: {{ auth()->user()->username }}</li>
    </ul>

    <form method="POST" action="{{ route('logout') }}" class="mt-3">
        @csrf
        <button class="btn btn-danger w-100">Logout</button>
    </form>

@endsection
