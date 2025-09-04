@extends('layouts.admin')


@section('content')
    <div class="p-4">
        <h2 class="text-xl font-bold mb-4">Gestion des Véhicules</h2>

        @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <input type="text" wire:model.debounce.500ms="search" class="border p-2 mb-4 w-full" placeholder="Rechercher...">

        <form wire:submit.prevent="{{ $isEdit ? 'update' : 'store' }}" class="mb-6 space-y-2">
            <div>
                <input type="text" wire:model.defer="marque" class="border p-2 w-full" placeholder="Marque">
                @error('marque') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <input type="text" wire:model.defer="modele" class="border p-2 w-full" placeholder="Modèle">
                @error('modele') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <input type="text" wire:model.defer="immatriculation" class="border p-2 w-full"
                    placeholder="Immatriculation">
                @error('immatriculation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <input type="file" wire:model="photo">
                @error('photo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="flex space-x-2">
                <button class="bg-blue-500 text-white px-4 py-2 rounded" type="submit">
                    {{ $isEdit ? 'Modifier' : 'Ajouter' }}
                </button>
                @if($isEdit)
                <button wire:click="resetForm" type="button"
                    class="bg-gray-500 text-white px-4 py-2 rounded">Annuler</button>
                @endif
            </div>
        </form>

        <table class="w-full border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2">Photo</th>
                    <th class="p-2">Marque</th>
                    <th class="p-2">Modèle</th>
                    <th class="p-2">Immatriculation</th>
                    <th class="p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vehicules as $vehicule)
                <tr class="border-t">
                    <td class="p-2">
                        @if($vehicule->photo)
                        <img src="{{ Storage::url($vehicule->photo) }}" class="w-20 h-12 object-cover">
                        @else
                        -
                        @endif
                    </td>
                    <td class="p-2">{{ $vehicule->marque }}</td>
                    <td class="p-2">{{ $vehicule->modele }}</td>
                    <td class="p-2">{{ $vehicule->immatriculation }}</td>
                    <td class="p-2">
                        <button wire:click="edit({{ $vehicule->id }})" class="text-blue-500">Modifier</button>
                        <button wire:click="destroy({{ $vehicule->id }})" class="text-red-500 ml-2">Supprimer</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Aucun véhicule trouvé.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $vehicules->links() }}
        </div>
    </div>
@endsection
