<div class="offcanvas offcanvas-end"
     tabindex="-1"
     id="notificationCanvas">

    <div class="offcanvas-header">
        <h5>Notifications</h5>
        <button type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body p-0">
        @forelse(auth()->user()->notifications as $notification)
            <div class="p-3 border-bottom
                {{ $notification->read_at ? '' : 'bg-light' }}">
                <div class="small">
                    {{ $notification->data['message'] ?? 'Notification' }}
                </div>
                <div class="text-muted small">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>
        @empty
            <div class="p-3 text-center text-muted">
                No notifications
            </div>
        @endforelse
    </div>
</div>
