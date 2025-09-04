<?php


namespace App\Livewire\Admin;

use App\Models\Vehicule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Vehicules extends Component
{
    use WithPagination, WithFileUploads;

    public $marque, $modele, $immatriculation, $vehiculeId, $photo;
    public $isEdit = false;
    public $search = '';

    protected $rules = [
        'marque' => 'required|string|max:255',
        'modele' => 'required|string|max:255',
        'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation',
        'photo' => 'nullable|image|max:2048',
    ];

    public function render()
    {
        $vehicules = Vehicule::where('admin_id', Auth::id())
            ->where(function ($query) {
                $query->where('marque', 'like', '%' . $this->search . '%')
                      ->orWhere('modele', 'like', '%' . $this->search . '%')
                      ->orWhere('immatriculation', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.vehicules', compact('vehicules'));
    }

    public function resetForm()
    {
        $this->marque = '';
        $this->modele = '';
        $this->immatriculation = '';
        $this->vehiculeId = null;
        $this->photo = null;
        $this->isEdit = false;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        $imagePath = null;
        if ($this->photo) {
            $imagePath = $this->photo->store('vehicules', 'public');
        }

        Vehicule::create([
            'admin_id' => Auth::id(),
            'marque' => $this->marque,
            'modele' => $this->modele,
            'immatriculation' => $this->immatriculation,
            'photo' => $imagePath,
        ]);

        session()->flash('success', 'Véhicule ajouté avec succès.');
        $this->resetForm();
    }

    public function edit($id)
    {
        $vehicule = Vehicule::findOrFail($id);
        $this->vehiculeId = $vehicule->id;
        $this->marque = $vehicule->marque;
        $this->modele = $vehicule->modele;
        $this->immatriculation = $vehicule->immatriculation;
        $this->isEdit = true;
    }

    public function update()
    {
        $vehicule = Vehicule::findOrFail($this->vehiculeId);

        $this->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'immatriculation' => 'required|string|max:255|unique:vehicules,immatriculation,' . $vehicule->id,
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($this->photo) {
            if ($vehicule->photo) {
                Storage::disk('public')->delete($vehicule->photo);
            }
            $vehicule->photo = $this->photo->store('vehicules', 'public');
        }

        $vehicule->update([
            'marque' => $this->marque,
            'modele' => $this->modele,
            'immatriculation' => $this->immatriculation,
            'photo' => $vehicule->photo,
        ]);

        session()->flash('success', 'Véhicule modifié.');
        $this->resetForm();
    }

    public function destroy($id)
    {
        $vehicule = Vehicule::findOrFail($id);
        if ($vehicule->photo) {
            Storage::disk('public')->delete($vehicule->photo);
        }
        $vehicule->delete();
        session()->flash('success', 'Véhicule supprimé.');
    }
}
