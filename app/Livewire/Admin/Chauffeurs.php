<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Chauffeurs extends Component
{
    public $name, $email, $password, $chauffeur_id;
    public $isEdit = false;

    public function render()
    {
        $chauffeurs = User::where('role', 'chauffeur')
                          ->where('admin_id', auth()->id())
                          ->get();

        return view('livewire.admin.chauffeurs', [
            'chauffeurs' => $chauffeurs
        ]);
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->chauffeur_id = null;
        $this->isEdit = false;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $this->chauffeur_id,
            'password' => $this->isEdit ? 'nullable' : 'required|min:6',
        ]);

        if ($this->isEdit) {
            $user = User::findOrFail($this->chauffeur_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password ? Hash::make($this->password) : $user->password,
            ]);
        } else {
            User::create([
                'admin_id' => auth()->id(),
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => 'chauffeur',
            ]);
        }

        $this->resetForm();
        session()->flash('success', 'Chauffeur enregistré avec succès');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->chauffeur_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->isEdit = true;
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', 'Chauffeur supprimé');
    }
}
