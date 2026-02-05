<div class="navbar navbar-expand-sm navbar-main navbar-dark bg-dark  pr-0" id="navbar" data-primary>
    <div class="container-fluid p-0">

        <!-- Navbar toggler -->

        <button class="navbar-toggler navbar-toggler-right d-block d-md-none" type="button" data-toggle="sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- Navbar Brand -->
        <a href="/admin/dashboard" class="navbar-brand ">
            <img class="navbar-brand-icon" src="{{ asset('dist/images/logo-sm.png') }}" width="22" alt="Stack">
            <span>Rose Online Delivery</span>
        </a>

        <ul class="nav navbar-nav ml-auto d-none d-md-flex">
            <li class="nav-item">
                <a href="{{ route('admin.upi') }}" class="nav-link" title="UPI Settings">
{{--                    <span class="material-icons">qr_code</span>--}}
                    <span class="material-symbols-outlined nav-icon">
upi_pay
</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                @php
                $count = auth()->user()->unreadNotifications->count();
                @endphp
                <a href="#notifications_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
                    <i class="material-icons nav-icon @if($count>0) navbar-notifications-indicator @endif">notifications</i>
                </a>
                <div id="notifications_menu" class="dropdown-menu dropdown-menu-right navbar-notifications-menu">
                    <div class="dropdown-item d-flex align-items-center py-2">
                        <span class="flex navbar-notifications-menu__title m-0">Notifications</span>
                        @if($count > 0)
                            <button id="adminMarkAllRead"
                                    class="btn btn-sm btn-outline-primary">
                                Mark all
                            </button>
                        @endif
                    </div>
                    <div class="navbar-notifications-menu__content" data-simplebar>
                        <div class="py-2">
                            @forelse(auth()->user()->unreadNotifications as $n)
                                <div class="dropdown-item admin-notif-item"
                                    data-id="{{ $n->id }}"
                                     data-settlement="{{ $n->data['settlement_id'] ?? '' }}"
                                     data-delivery="{{ $n->data['delivery_id'] ?? '' }}"
                                >
                                    <div class="mr-3 fw-bold">
                                        {{ $n->data['title'] ?? 'Notification' }}
                                    </div>

                                        <div class="small text-muted">
                                            {{ $n->data['message'] ?? '' }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $n->created_at->diffForHumans() }}
                                        </small>

                                </div>
                            @empty
                                <p class="text-muted text-center">
                                    No notifications
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <ul class="nav navbar-nav d-none d-sm-flex border-left navbar-height align-items-center">
            <li class="nav-item dropdown">
                <a href="#account_menu" class="nav-link dropdown-toggle" data-toggle="dropdown" data-caret="false">
                    <img src="{{ asset('dist/images/avatar/demi.png') }}" class="rounded-circle" width="32" alt="Frontted">
                    <span class="ml-1 d-flex-inline">
                        <span class="text-light">{{ auth()->user()->name }}</span>
                    </span>
                </a>
                <div id="account_menu" class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-item-text dropdown-item-text--lh">
                        <div><strong>{{ auth()->user()->name }}</strong></div>
                        <div>{{ auth()->user()->role }}</div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">Edit Profile</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>

    </div>
</div>
