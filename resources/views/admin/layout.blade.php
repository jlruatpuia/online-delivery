<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | Rose Online Delivery</title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Simplebar -->
    <link type="text/css" href="{{ asset('dist/vendor/simplebar.min.css') }}" rel="stylesheet">

    <!-- App CSS -->
{{--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">--}}
    <link type="text/css" href="{{ asset('dist/css/app.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('dist/css/app.rtl.css') }}" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="{{ asset('dist/css/vendor-material-icons.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('dist/css/vendor-material-icons.rtl.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL@20..48,100..700,0..1" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="{{ asset('dist/css/vendor-fontawesome-free.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('dist/css/vendor-fontawesome-free.rtl.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <style>
        .admin-notif-item {
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .admin-notif-item:hover {
            background: #f8f9fa;
        }
    </style>
    @yield('styles')
</head>

<body class="layout-default">

<div class="preloader"></div>

<!-- Header Layout -->
<div class="mdk-header-layout js-mdk-header-layout">

    <!-- Header -->

    <div id="header" class="mdk-header js-mdk-header m-0" data-fixed>
        <div class="mdk-header__content">

            @include('admin.navbar')

        </div>
    </div>

    <!-- // END Header -->

    <!-- Header Layout Content -->
    <div class="mdk-header-layout__content">

        <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
            @yield('content')
            <!-- // END drawer-layout__content -->

            @include('admin.sidebar')
        </div>
        <!-- // END drawer-layout -->

    </div>
    <!-- // END header-layout__content -->

</div>
<!-- // END header-layout -->

<!-- App Settings FAB -->
<div id="app-settings">
    <app-settings layout-active="default" :layout-location="{
      'default': 'ui-tabs.html',
      'fixed': 'fixed-ui-tabs.html',
      'fluid': 'fluid-ui-tabs.html',
      'mini': 'mini-ui-tabs.html'
    }"></app-settings>
</div>

<!-- jQuery -->
<script src="{{ asset('dist/vendor/jquery.min.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('dist/vendor/popper.min.js') }}"></script>
<script src="{{ asset('dist/vendor/bootstrap.min.js') }}"></script>

<!-- Simplebar -->
<script src="{{ asset('dist/vendor/simplebar.min.js') }}"></script>

<!-- DOM Factory -->
<script src="{{ asset('dist/vendor/dom-factory.js') }}"></script>

<!-- MDK -->
<script src="{{ asset('dist/vendor/material-design-kit.js') }}"></script>

<!-- App -->
<script src="{{ asset('dist/js/toggle-check-all.js') }}"></script>
<script src="{{ asset('dist/js/check-selected-row.js') }}"></script>
<script src="{{ asset('dist/js/dropdown.js') }}"></script>
<script src="{{ asset('dist/js/sidebar-mini.js') }}"></script>
{{--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>--}}
<script src="{{ asset('dist/js/app.js') }}"></script>


<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.toast').forEach(toastEl => {
            new bootstrap.Toast(toastEl).show();
        });
    });
</script>
<script>
    document.querySelectorAll('.admin-notif-item')
        .forEach(item => {

            item.addEventListener('click', () => {
                const id = item.dataset.id;
                const settlementId = item.dataset.settlement;
                const deliveryId = item.dataset.delivery;

                // Mark as read/notifications/{id}/read
                fetch(`/admin/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    removeAdminNotification(item);
                });

                // Navigate
                if (settlementId) {
                    window.location.href =
                        `/admin/settlements/${settlementId}`;
                } else if (deliveryId) {
                    window.location.href =
                        `/admin/deliveries/${deliveryId}`;
                }
            });
        });

    // 🔘 Mark all as read
    document.getElementById('adminMarkAllRead')
        ?.addEventListener('click', () => {

            fetch('/api/admin/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(() => {
                document
                    .querySelectorAll('.admin-notif-item')
                    .forEach(i => i.remove());

                updateAdminBadge(0);
                showEmptyAdminNotif();
            });
        });

    function removeAdminNotification(item) {
        item.remove();
        decrementAdminBadge();
        showEmptyAdminNotif();
    }

    function decrementAdminBadge() {
        const badge = document.getElementById('adminNotifBadge');
        if (!badge) return;

        let count = parseInt(badge.innerText || '0');
        count = Math.max(count - 1, 0);

        badge.innerText = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    function updateAdminBadge(count) {
        const badge = document.getElementById('adminNotifBadge');
        if (!badge) return;

        badge.innerText = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    function showEmptyAdminNotif() {
        const list = document.getElementById('adminNotificationList');
        if (list.children.length === 0) {
            list.innerHTML =
                '<p class="text-muted text-center">No notifications</p>';
        }
    }
</script>

@yield('scripts')
<!-- Toast Container -->
<div class="toast-container position-fixed top-0 end-0 p-3"
     style="z-index: 1080">

    @if(session('success'))
        <div class="toast align-items-center text-bg-success border-0"
             role="alert"
             data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    ✅ {{ session('success') }}
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast">
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast align-items-center text-bg-danger border-0"
             role="alert"
             data-bs-delay="4000">
            <div class="d-flex">
                <div class="toast-body">
                    ❌ {{ session('error') }}
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast">
                </button>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="toast text-bg-warning border-0" data-bs-delay="4000">
            <div class="toast-body">
                ⚠️ {{ session('warning') }}
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="toast align-items-center text-bg-danger border-0"
             role="alert"
             data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    ❌ {{ $errors->first() }}
                </div>
                <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast">
                </button>
            </div>
        </div>
    @endif

</div>

</body>

</html>
