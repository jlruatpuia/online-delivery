@extends('admin.layout')
@section('title', 'Customers')
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
                            <li class="breadcrumb-item active" aria-current="page">Customers</li>
                        </ol>
                    </nav>
                    <h1 class="m-0">Customers</h1>
                </div>
            </div>
        </div>
        <div class="container-fluid page__container">
            <div class="card">
                <h5 class="card-header">Customers List</h5>
                <div class="card-body">
                    <table id="deliveriesTable" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Sl. No</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Phone No</th>
                            <th>Map Location</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $c)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->address }}</td>
                                <td>{{ $c->phone_no }}</td>
                                @if($c->map_location)
                                    <td>
                                    Lat: {{ $c->map_location['lat'] }}, Lng: {{ $c->map_location['lng'] }}
                                    </td>
                                    <td>
                                        @php
                                            $lat = $c->map_location['lat'];
                                            $lng = $c->map_location['lng'];
                                            $label = urlencode($c->name . '\n' . $c->address . '\nPhone: ' . $c->phone_no);
                                            $mapUrl =
                                                "https://www.google.com/maps/search/?api=1".
                                                "&query={$lat},{$lng}".
                                                "&query_place_id={$label}";
                                        @endphp
                                        <a href="{{ $mapUrl }}"
                                           target="_blank"
                                           class="btn btn-primary">
                                            📍 Navigate
                                        </a>
                                    </td>
                                @else
                                    <td>
                                    Not Available
                                    </td>
                                    <td>
                                        <button
                                           class="btn btn-outline-primary disabled">
                                            📍 Navigate
                                        </button>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script>
        flatpickr("#dateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d-m-Y",
            maxDate: "today",
            defaultDate: [
                "{{ now()->startOfMonth()->toDateString() }}",
                "{{ now()->toDateString() }}"
            ]
        });
    </script>
    <script>
        $(document).ready(function () {

            $('#deliveriesTable').DataTable({
                pageLength: 10,
                lengthChange: false,
                ordering: true,
                searching: true,
                info: true,
                autoWidth: false,

                columnDefs: [
                    { orderable: false, targets: 5 } // Action column
                ]
            });

        });
    </script>

@endsection
