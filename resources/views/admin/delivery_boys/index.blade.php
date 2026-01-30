@extends('admin.layout')

@section('title', 'Delivery Boys')

@section('content')

    <section class="content-header">
        <h1>Delivery Boys</h1>
    </section>

    <section class="content">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('admin.delivery_boys.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Delivery Boy
                </a>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered table-striped">
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
                            <td colspan="4" class="text-center text-muted">No records</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
