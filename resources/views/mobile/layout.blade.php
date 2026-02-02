<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Delivery App')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            padding-bottom: 70px; /* space for bottom nav */
        }
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 60px;
            background: #fff;
            border-top: 1px solid #ddd;
            z-index: 1050;
        }
        .nav-icon {
            font-size: 1.4rem;
        }
        .swipe-container {
            position: relative;
            overflow: hidden;
        }

        .swipe-card {
            position: relative;
            z-index: 2;
            transition: transform 0.25s ease;
        }

        .swipe-action {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
            z-index: 1;
        }

        .swipe-left {
            left: 0;
            border-radius: 8px 0 0 8px;
            background: #0d6efd; /* call - blue */
        }

        .swipe-right {
            right: 0;
            border-radius: 0 8px 8px 0;
            background: #198754; /* navigate - green */
        }

    </style>
</head>
<body>

<!-- TOP BAR -->
<nav class="navbar navbar-light bg-white shadow-sm sticky-top">
    <div class="container-fluid">
        <span class="fw-bold">
            @yield('title')
        </span>
        @auth
        <!-- Notification Bell -->
        <button class="btn position-relative"
                data-bs-toggle="offcanvas"
                data-bs-target="#notificationCanvas">
            <i class="bi bi-bell fs-4"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ auth()->user()->unreadNotifications->count() }}
            </span>
        </button>
        @endauth
    </div>
</nav>
<div class="container my-3">
    @if(session('success'))
        <div class="alert alert-success py-2">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger py-2">
            {{ session('error') }}
        </div>
    @endif

    @yield('content')
</div>
{{--<div class="position-fixed top-0 start-50 translate-middle-x mt-2 z-3"--}}
{{--     style="width:95%; max-width:420px;">--}}

{{--    @if(session('success'))--}}
{{--        <div class="toast align-items-center text-bg-success show mb-2">--}}
{{--            <div class="d-flex">--}}
{{--                <div class="toast-body">--}}
{{--                    {{ session('success') }}--}}
{{--                </div>--}}
{{--                <button class="btn-close btn-close-white me-2"--}}
{{--                        data-bs-dismiss="toast"></button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}

{{--    @if(session('error'))--}}
{{--        <div class="toast align-items-center text-bg-warning show mb-2">--}}
{{--            <div class="d-flex">--}}
{{--                <div class="toast-body">--}}
{{--                    {{ session('error') }}--}}
{{--                </div>--}}
{{--                <button class="btn-close me-2"--}}
{{--                        data-bs-dismiss="toast"></button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
{{--</div>--}}
{{--<!-- PAGE CONTENT -->--}}
{{--<div class="container ">--}}
{{--    @yield('content')--}}
{{--</div>--}}
@auth
@include('mobile.notifications')
@include('mobile.partials.bottom-nav')
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<div id="offlineBanner"
     class="alert alert-danger text-center position-fixed bottom-0 w-100"
     style="display:none; z-index:1060;">
    ⚠️ You are offline. Some actions may not work.
</div>

<script>
    function updateConnectionStatus() {
        document.getElementById('offlineBanner').style.display =
            navigator.onLine ? 'none' : 'block';
    }

    window.addEventListener('online', updateConnectionStatus);
    window.addEventListener('offline', updateConnectionStatus);
    updateConnectionStatus();
</script>
</body>
</html>
