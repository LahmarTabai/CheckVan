<?php

namespace App\Livewire\Chauffeur;

use App\Models\Affectation;
use App\Models\Dommage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DommageInterface extends Component
{
    use WithFileUploads;

    public $affectationId;
    public $affectation;
    public $dommages = [];
    public $showForm = false;
    public $editingDommage = null;

    // Propriétés du formulaire
    public $type = 'autre';
    public $description = '';
    public $severite = 'mineur';
    public $photo;
    public $coord_x;
    public $coord_y;
    public $coord_z;

    protected $rules = [
        'type' => 'required|in:rayure,bosse,choc,autre',
        'description' => 'nullable|string|max:1000',
        'severite' => 'required|in:mineur,moyen,majeur',
        'photo' => 'nullable|image|max:2048',
        'coord_x' => 'nullable|numeric|between:0,100',
        'coord_y' => 'nullable|numeric|between:0,100',
        'coord_z' => 'nullable|numeric|between:0,100',
    ];

    public function mount($affectationId)
    {
        $this->affectationId = $affectationId;
        $this->affectation = Affectation::with(['vehicule', 'dommages'])->findOrFail($affectationId);
        $this->loadDommages();
    }

    public function loadDommages()
    {
        $this->dommages = $this->affectation->dommages()->orderBy('created_at', 'desc')->get();
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->type = 'autre';
        $this->description = '';
        $this->severite = 'mineur';
        $this->photo = null;
        $this->coord_x = null;
        $this->coord_y = null;
        $this->coord_z = null;
        $this->editingDommage = null;
    }

    public function editDommage($dommageId)
    {
        $dommage = Dommage::findOrFail($dommageId);
        $this->editingDommage = $dommage;
        $this->type = $dommage->type;
        $this->description = $dommage->description;
        $this->severite = $dommage->severite;
        $this->coord_x = $dommage->coord_x;
        $this->coord_y = $dommage->coord_y;
        $this->coord_z = $dommage->coord_z;
        $this->showForm = true;
    }

    public function saveDommage()
    {
        $this->validate();

        $data = [
            'affectation_id' => $this->affectationId,
            'chauffeur_id' => auth()->user()->id,
            'type' => $this->type,
            'description' => $this->description,
            'severite' => $this->severite,
            'coord_x' => $this->coord_x,
            'coord_y' => $this->coord_y,
            'coord_z' => $this->coord_z,
        ];

        // Gestion de la photo
        if ($this->photo) {
            $photoPath = $this->photo->store('dommages', 'public');
            $data['photo_path'] = $photoPath;
        }

        if ($this->editingDommage) {
            // Mise à jour
            if ($this->photo && $this->editingDommage->photo_path) {
                Storage::disk('public')->delete($this->editingDommage->photo_path);
            }
            $this->editingDommage->update($data);
            session()->flash('message', 'Dommage modifié avec succès !');
        } else {
            // Création
            Dommage::create($data);
            session()->flash('message', 'Dommage signalé avec succès !');
        }

        $this->loadDommages();
        $this->resetForm();
        $this->showForm = false;
    }

    public function deleteDommage($dommageId)
    {
        $dommage = Dommage::findOrFail($dommageId);

        // Supprimer la photo si elle existe
        if ($dommage->photo_path) {
            Storage::disk('public')->delete($dommage->photo_path);
        }

        $dommage->delete();
        $this->loadDommages();
        session()->flash('message', 'Dommage supprimé avec succès !');
    }

    public function markAsRepared($dommageId)
    {
        $dommage = Dommage::findOrFail($dommageId);
        $dommage->update(['reparé' => true]);
        $this->loadDommages();
        session()->flash('message', 'Dommage marqué comme réparé !');
    }

    public function render()
    {
        return view('livewire.chauffeur.dommage-interface');
    }
}
