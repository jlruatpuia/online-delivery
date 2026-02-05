<div class="mdk-drawer  js-mdk-drawer" id="default-drawer" data-align="start">
    <div class="mdk-drawer__content">
        <div class="sidebar sidebar-light sidebar-left simplebar" data-simplebar>
            <div class="sidebar-heading sidebar-m-t">Menu</div>
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item {{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="/admin/dashboard">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-icons">dvr</i>
                        <span class="sidebar-menu-text">Dashboards</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ request()->is('admin/settlements*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.settlements.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-symbols-outlined">handshake</i>
                        <span class="sidebar-menu-text">Settlements</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ request()->is('admin/deliveries*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.deliveries.index') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-symbols-outlined">delivery_truck_speed</i>
                        <span class="sidebar-menu-text">Deliveries</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ request()->is('admin/customers*') ? 'active' : '' }}">
                    <a class="sidebar-menu-button" href="{{ route('admin.customers') }}">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-symbols-outlined">groups</i>
                        <span class="sidebar-menu-text">Customers</span>
                    </a>
                </li>
                <li class="sidebar-menu-item {{ request()->is('admin/delivery_boys*') ? 'active open' : '' }}">
                    <a class="sidebar-menu-button" data-toggle="collapse" href="#deliveryboys_menu">
                        <i class="sidebar-menu-icon sidebar-menu-icon--left material-symbols-outlined">moped_package</i>
                        <span class="sidebar-menu-text">Delivery Boys</span>
                        <span class="ml-auto sidebar-menu-toggle-icon"></span>
                    </a>
                    <ul class="sidebar-submenu collapse show " id="deliveryboys_menu">
                        <li class="sidebar-menu-item {{ request()->is('admin/delivery-boys*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.delivery_boys.index') }}">
                                <span class="sidebar-menu-text">View</span>
                            </a>
                        </li>
                        <li class="sidebar-menu-item {{ request()->is('admin/delivery-boys-performance*') ? 'active' : '' }}">
                            <a class="sidebar-menu-button" href="{{ route('admin.delivery_boys.performance') }}">
                                <span class="sidebar-menu-text">Performance</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

        </div>
    </div>
</div>
