@extends('admin.layout')

@section('title', 'Delivery Boys')

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
                            <li class="breadcrumb-item active" aria-current="page">Delivery Boys</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Delivery Boys</h1>
                </div>
                <a href="{{ route('admin.delivery_boys.create') }}" class="btn btn-success ml-3">Add New</a>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="card">
                <h5 class="card-header">Delivery Boys</h5>
                <div class="card-body">
                    <table id="deliveryBoyTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Created</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($deliveryBoys as $boy)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $boy->name }}</td>
                                <td>{{ $boy->username }}</td>
                                <td>{{ $boy->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($boy->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>

                                <td>
                                    <form method="POST"
                                          action="{{ route('admin.delivery_boys.toggle', $boy) }}"
                                          onsubmit="return confirm('Change status?')">
                                        @csrf

                                        @if($boy->is_active)
                                            <button class="btn btn-sm btn-danger">
                                                Deactivate
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-success">
                                                Activate
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No records</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            $('#deliveryBoyTable').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                lengthChange: false,

            });
        });
    </script>
@endsection
