<div class="container py-4">
    <h2 class="mb-4">Notifications</h2>

    <div class="mb-3">
        <select wire:model="status" class="form-select w-auto d-inline-block">
            <option value="">Toutes</option>
            <option value="unread">Non lues</option>
            <option value="read">Lues</option>
        </select>
    </div>

    <ul class="list-group">
        @forelse($notifications as $notification)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $notification->data['title'] ?? 'Notification' }}</strong><br>
                <small>{{ $notification->data['message'] ?? '' }}</small><br>
                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
            </div>

            <div>
                @if(is_null($notification->read_at))
                <button class="btn btn-sm btn-success" wire:click="markAsRead('{{ $notification->id }}')">
                    Marquer comme lue
                </button>
                @else
                <span class="badge bg-secondary">Lue</span>
                @endif
            </div>
        </li>
        @empty
        <li class="list-group-item">Aucune notification.</li>
        @endforelse
    </ul>

    <div class="mt-3">
        {{ $notifications->links() }}
    </div>
</div>
