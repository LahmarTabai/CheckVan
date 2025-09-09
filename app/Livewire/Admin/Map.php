<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class Map extends Component
{
    public $locations = [];

    public function refresh()
    {
        $adminId = Auth::id();
        $this->locations = Location::query()
            ->whereHas('chauffeur', fn($q) => $q->where('admin_id', $adminId))
            ->latest('recorded_at')
            ->take(100)
            ->get(['latitude', 'longitude', 'recorded_at'])
            ->toArray();
    }

    public function render()
    {
        if (empty($this->locations)) {
            $this->refresh();
        }
        return view('livewire.admin.map');
    }
}



