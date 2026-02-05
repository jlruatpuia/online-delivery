<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="notificationCanvas">

    <div class="offcanvas-header">
        <h5>🔔 Notifications</h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body p-0" id="notificationList">
        @forelse(auth()->user()->unreadNotifications as $n)
            <div class="swipe-container mb-2" data-id="{{ $n->id }}">
                <div class="swipe-bg">
                    ✔ Mark as read
                </div>

                <div class="swipe-card p-3 rounded {{ $n->read_at ? 'bg-light' : 'bg-white fw-bold' }}">
                    <div class="small">
                        {{ $n->data['title'] ?? 'Notification' }}
                    </div>
                    <small class="text-muted">
                        {{ $n->created_at->diffForHumans() }}
                    </small>
                </div>
            </div>
        @empty
            <p class="text-muted text-center">
                No Notifications
            </p>
        @endforelse
        <div class="p-3">
            <button id="markAllRead" class="btn btn-sm btn-outline-primary w-100">Mark all as Read</button>
        </div>

    </div>
</div>
