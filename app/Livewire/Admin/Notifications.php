<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Notifications extends Component
{
    use WithPagination;

    public $status = '';

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
        }
    }

    public function render()
    {
        $notifications = Auth::user()
            ->notifications()
            ->when($this->status === 'unread', fn($query) => $query->unread())
            ->when($this->status === 'read', fn($query) => $query->whereNotNull('read_at'))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.notifications', compact('notifications'))->layout('layouts.admin');
    }
}
