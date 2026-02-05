@extends('mobile.layout')

@section('title', 'My Deliveries')

@section('content')
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-pills card-header-pills mb-3">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#pending">
                        Pending ({{ $pending->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#completed">
                        Completed ({{ $completed->count() }})
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#cancelled">
                        Others ({{ $cancelled->count() }})
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="pending">
                    <div class="tab-pane fade show active" id="pending">
                        @forelse($pending as $delivery)
                            @include('mobile.deliveries._card', ['delivery' => $delivery])
                        @empty
                            <div class="text-center text-muted">
                                No pending deliveries
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="tab-pane fade" id="completed">

                    @forelse($completed as $delivery)
                        @include('mobile.deliveries._card', ['delivery' => $delivery])
                    @empty
                        <div class="text-center text-muted">
                            No completed deliveries
                        </div>
                    @endforelse

                </div>
                <div class="tab-pane fade" id="cancelled">

                    @forelse($cancelled as $delivery)
                        @include('mobile.deliveries._card', ['delivery' => $delivery])
                    @empty
                        <div class="text-center text-muted">
                            No cancelled deliveries
                        </div>
                    @endforelse

                </div>
            </div> {{-- tab-content --}}
        </div>
    </div>




{{--    <!-- LONG PRESS ACTION SHEET -->--}}
{{--    <div class="offcanvas offcanvas-bottom"--}}
{{--         tabindex="-1"--}}
{{--         id="deliveryActionSheet">--}}
{{--        <div class="offcanvas-header">--}}
{{--            <h6 class="fw-bold mb-0">Delivery Actions</h6>--}}
{{--            <button class="btn-close" data-bs-dismiss="offcanvas"></button>--}}
{{--        </div>--}}

{{--        <div class="offcanvas-body">--}}
{{--            <a id="actionCall" class="btn btn-outline-primary w-100 mb-2">--}}
{{--                📞 Call Customer--}}
{{--            </a>--}}

{{--            <a id="actionNavigate" class="btn btn-outline-success w-100 mb-2">--}}
{{--                📍 Navigate--}}
{{--            </a>--}}

{{--            <a id="actionOpen" class="btn btn-primary w-100">--}}
{{--                📦 Open Delivery--}}
{{--            </a>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <script>--}}
{{--        let pressTimer;--}}

{{--        function startPress(e, openUrl, phone, mapLocation) {--}}
{{--            pressTimer = setTimeout(() => {--}}

{{--                // Set actions--}}
{{--                document.getElementById('actionOpen').href = openUrl;--}}
{{--                document.getElementById('actionCall').href = 'tel:' + phone;--}}

{{--                if (mapLocation) {--}}
{{--                    document.getElementById('actionNavigate').href ="{{ $delivery['navigation_url'] }}";--}}
{{--                    document.getElementById('actionNavigate').classList.remove('disabled');--}}
{{--                } else {--}}
{{--                    document.getElementById('actionNavigate').href = '#';--}}
{{--                    document.getElementById('actionNavigate').classList.add('disabled');--}}
{{--                }--}}

{{--                // Show bottom sheet--}}
{{--                new bootstrap.Offcanvas(--}}
{{--                    document.getElementById('deliveryActionSheet')--}}
{{--                ).show();--}}

{{--            }, 800); // 500ms = long press--}}
{{--        }--}}

{{--        function cancelPress() {--}}
{{--            clearTimeout(pressTimer);--}}
{{--        }--}}
{{--    </script>--}}
@endsection
