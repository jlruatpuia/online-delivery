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

        .swipe-bg {
            position: absolute;
            inset: 0;
            background: #d1e7dd;
            color: #0f5132;
            display: flex;
            align-items: center;
            padding-left: 16px;
            font-weight: 600;
        }

        .swipe-card {
            position: relative;
            background: #fff;
            transition: transform 0.2s ease;
        }

        .off-canvas-form-content{
            padding-bottom: 120px;
        }

        .offcanvas-footer{
            position: sticky;
            bottom: 0;
            background: #fff;
            padding: 12px;
            padding-bottom: 70px;
            border-top: 1px solid #eee;
        }
        /*.delivery-request-form {*/
        /*    !*position: sticky;*!*/
        /*    !*bottom: 0;*!*/
        /*    padding-bottom: 100px;*/
        /*}*/
    </style>
    @yield('styles')
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
            <span id="notifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
<script>
    document.addEventListener('DOMContentLoaded', () => {

        document.querySelectorAll('.swipe-container')
            .forEach(container => {

                const card = container.querySelector('.swipe-card');
                let startX = 0, currentX = 0, swiping = false;

                card.addEventListener('touchstart', e => {
                    startX = e.touches[0].clientX;
                    swiping = true;
                });

                card.addEventListener('touchmove', e => {
                    if (!swiping) return;

                    currentX = e.touches[0].clientX - startX;
                    if (currentX < 0) return;

                    card.style.transform =
                        `translateX(${currentX}px)`;
                });

                card.addEventListener('touchend', () => {
                    swiping = false;

                    if (currentX > 80) {
                        markRead(container.dataset.id, container);
                    } else {
                        card.style.transform = 'translateX(0)';
                    }
                    currentX = 0;
                });
            });

        // 🔘 Mark all as read
        document.getElementById('markAllRead')
            ?.addEventListener('click', () => {

                fetch('/mobile/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    document.querySelectorAll('.swipe-container')
                        .forEach(item => {
                            item.style.transition = 'opacity 0.2s ease';
                            item.style.opacity = '0';
                            setTimeout(() => item.remove(), 200);
                        });

                    updateNotificationBadge(0);
                    checkEmptyNotificationList();
                    // document
                    //     .querySelectorAll('.swipe-card')
                    //     .forEach(card => card.classList.remove('fw-bold'));
                    //
                    // updateNotificationBadge(0);
                });
            });
    });

    function markRead(id, container) {
        fetch(`/mobile/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => {
            container.style.transition = 'opacity 0.2s ease';
            container.style.opacity = '0';

            setTimeout(() => {
                container.remove();
                decrementNotificationBadge(0);
                checkEmptyNotificationList();
                }, 200
            );
            // const card = container.querySelector('.swipe-card');
            // card.classList.remove('fw-bold');
            // card.style.transform = 'translateX(0)';
            //
            // decrementNotificationBadge();
        });
    }
</script>
<script>
    function decrementNotificationBadge() {
        const badge = document.getElementById('notifBadge');
        if (!badge) return;

        let count = parseInt(badge.innerText || '0');
        count = Math.max(count - 1, 0);

        badge.innerText = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    function updateNotificationBadge(count) {
        const badge = document.getElementById('notifBadge');
        if (!badge) return;

        badge.innerText = count;
        badge.style.display = count > 0 ? 'inline-block' : 'none';
    }

    function checkEmptyNotificationList() {
        const list = document.getElementById('notificationList');

        if (!list || list.children.length > 0) return;

        list.innerHTML = `
        <p class="text-muted text-center mt-3">
            No notifications
        </p>
    `;
    }
</script>
</body>
</html>
