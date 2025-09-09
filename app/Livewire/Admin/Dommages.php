<?php

namespace App\Livewire\Admin;

use App\Models\Dommage;
use App\Models\Affectation;
use Livewire\Component;
use Livewire\WithPagination;

class Dommages extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterSeverite = '';
    public $filterStatus = '';
    public $selectedDommage = null;
    public $showModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterSeverite' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterSeverite()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function viewDommage($dommageId)
    {
        $this->selectedDommage = Dommage::with(['affectation.vehicule', 'chauffeur'])
            ->where('id', $dommageId)
            ->whereHas('affectation.vehicule', function($query) {
                $query->where('admin_id', auth()->id());
            })
            ->first();

        $this->showModal = true;
    }

    public function markAsRepared($dommageId)
    {
        $dommage = Dommage::where('id', $dommageId)
            ->whereHas('affectation.vehicule', function($query) {
                $query->where('admin_id', auth()->id());
            })
            ->first();

        if ($dommage) {
            $dommage->update(['reparé' => true]);
            session()->flash('message', 'Dommage marqué comme réparé !');
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedDommage = null;
    }

    public function render()
    {
        $query = Dommage::with(['affectation.vehicule', 'chauffeur'])
            ->whereHas('affectation.vehicule', function($q) {
                $q->where('admin_id', auth()->id());
            });

        // Filtres
        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('affectation.vehicule', function($subQ) {
                      $subQ->where('marque', 'like', '%' . $this->search . '%')
                           ->orWhere('modele', 'like', '%' . $this->search . '%')
                           ->orWhere('immatriculation', 'like', '%' . $this->search . '%');
                  })
                  ->orWhereHas('chauffeur', function($subQ) {
                      $subQ->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        if ($this->filterSeverite) {
            $query->where('severite', $this->filterSeverite);
        }

        if ($this->filterStatus) {
            if ($this->filterStatus === 'reparé') {
                $query->where('reparé', true);
            } elseif ($this->filterStatus === 'en_attente') {
                $query->where('reparé', false);
            }
        }

        $dommages = $query->orderBy('created_at', 'desc')->paginate(10);

        // Statistiques
        $stats = [
            'total' => Dommage::whereHas('affectation.vehicule', function($q) {
                $q->where('admin_id', auth()->id());
            })->count(),
            'non_repares' => Dommage::whereHas('affectation.vehicule', function($q) {
                $q->where('admin_id', auth()->id());
            })->where('reparé', false)->count(),
            'majeurs' => Dommage::whereHas('affectation.vehicule', function($q) {
                $q->where('admin_id', auth()->id());
            })->where('severite', 'majeur')->count(),
        ];

        return view('livewire.admin.dommages', compact('dommages', 'stats'));
    }
}
