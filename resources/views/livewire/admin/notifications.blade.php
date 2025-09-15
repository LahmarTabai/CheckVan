<div>
    <div class="d-flex align-items-center mb-4">
        <div class="me-3">
            <div class="glass-effect rounded-circle p-3">
                <i class="fas fa-bell text-gradient fs-4"></i>
            </div>
        </div>
        <div>
            <h1 class="text-gradient mb-0">Notifications</h1>
            <p class="text-muted mb-0">Centre de notifications 2050</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card-2050 mb-4 hover-lift">
        <div class="card-body-2050 p-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="form-label-2050">Filtrer par statut</label>
                    <select wire:model="status" class="form-control-2050">
                        <option value="">Toutes les notifications</option>
                        <option value="unread">Non lues</option>
                        <option value="read">Lues</option>
                    </select>
                </div>
                <div class="col-md-6 text-end">
                    <div class="notification-stats-2050">
                        <span class="badge badge-primary-2050 me-2">
                            <i class="fas fa-bell me-1"></i>
                            {{ $notifications->whereNull('read_at')->count() }} Non lues
                        </span>
                        <span class="badge badge-success-2050">
                            <i class="fas fa-check me-1"></i>
                            {{ $notifications->whereNotNull('read_at')->count() }} Lues
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des Notifications -->
    <div class="card-2050 hover-lift">
        <div class="card-header-2050">
            <h6 class="mb-0">
                <i class="fas fa-list me-2"></i>Liste des Notifications
            </h6>
        </div>
        <div class="card-body p-0">
            @forelse($notifications as $notification)
                <div class="notification-item-2050 {{ is_null($notification->read_at) ? 'unread' : 'read' }}">
                    <div class="notification-content-2050">
                        <div class="notification-icon-2050">
                            <i
                                class="fas fa-{{ str_contains($notification->data['title'] ?? '', 'Tâche')
                                    ? 'tasks'
                                    : (str_contains($notification->data['title'] ?? '', 'Véhicule')
                                        ? 'car'
                                        : (str_contains($notification->data['title'] ?? '', 'Chauffeur')
                                            ? 'user'
                                            : 'bell')) }}"></i>
                        </div>
                        <div class="notification-details-2050">
                            <h6 class="notification-title-2050">
                                {{ $notification->data['title'] ?? 'Notification' }}
                                @if (is_null($notification->read_at))
                                    <span class="unread-indicator-2050"></span>
                                @endif
                            </h6>
                            <p class="notification-message-2050">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                            <div class="notification-meta-2050">
                                <i class="fas fa-clock me-1"></i>
                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar me-1"></i>
                                <span>{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>
                        <div class="notification-actions-2050">
                            @if (is_null($notification->read_at))
                                <button class="btn btn-success-2050 btn-sm"
                                    wire:click="markAsRead('{{ $notification->id }}')">
                                    <i class="fas fa-check me-1"></i>
                                    Marquer comme lue
                                </button>
                            @else
                                <span class="badge badge-success-2050">
                                    <i class="fas fa-check me-1"></i>
                                    Lue
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state-2050 text-center py-5">
                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Aucune notification</h5>
                    <p class="text-muted">Vous n'avez pas encore de notifications</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if ($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
