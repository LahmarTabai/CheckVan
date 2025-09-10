<?php

namespace App\Services;

use App\Models\Vehicule;
use App\Models\User;
use App\Models\Affectation;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportService
{
    public static function exportVehicules($filters = [])
    {
        $query = Vehicule::with(['marque', 'modele', 'photos'])
            ->where('admin_id', Auth::user()->user_id);

        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('immatriculation', 'like', '%' . $filters['search'] . '%')
                  ->orWhereHas('marque', function ($q) use ($filters) {
                      $q->where('nom', 'like', '%' . $filters['search'] . '%');
                  })
                  ->orWhereHas('modele', function ($q) use ($filters) {
                      $q->where('nom', 'like', '%' . $filters['search'] . '%');
                  });
            });
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        if (!empty($filters['marque_id'])) {
            $query->where('marque_id', $filters['marque_id']);
        }

        if (!empty($filters['modele_id'])) {
            $query->where('modele_id', $filters['modele_id']);
        }

        $vehicules = $query->get();

        return Excel::download(new class($vehicules) implements FromCollection, WithHeadings, WithMapping, WithStyles {
            private $vehicules;

            public function __construct($vehicules)
            {
                $this->vehicules = $vehicules;
            }

            public function collection()
            {
                return $this->vehicules;
            }

            public function headings(): array
            {
                return [
                    'Immatriculation',
                    'Marque',
                    'Modèle',
                    'Type',
                    'Année',
                    'Couleur',
                    'Kilométrage',
                    'Statut',
                    'Prix d\'achat',
                    'Date d\'achat',
                    'Prix location/jour',
                    'Date de location',
                    'Prochaine révision',
                    'Nombre de photos',
                    'Date de création'
                ];
            }

            public function map($vehicule): array
            {
                return [
                    $vehicule->immatriculation,
                    $vehicule->marque->nom ?? 'N/A',
                    $vehicule->modele->nom ?? 'N/A',
                    ucfirst($vehicule->type),
                    $vehicule->annee,
                    $vehicule->couleur,
                    number_format($vehicule->kilometrage ?? 0),
                    ucfirst(str_replace('_', ' ', $vehicule->statut)),
                    $vehicule->prix_achat ? number_format($vehicule->prix_achat, 2) . ' €' : '',
                    $vehicule->date_achat,
                    $vehicule->prix_location ? number_format($vehicule->prix_location, 2) . ' €' : '',
                    $vehicule->date_location,
                    $vehicule->prochaine_revision,
                    $vehicule->photos->count(),
                    $vehicule->created_at->format('d/m/Y H:i')
                ];
            }

            public function styles(Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }
        }, 'vehicules_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public static function exportChauffeurs($filters = [])
    {
        $query = User::where('role', 'chauffeur')
                     ->where('admin_id', Auth::user()->user_id);

        // Appliquer les filtres
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nom', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('prenom', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('tel', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['statut'])) {
            $query->where('statut', $filters['statut']);
        }

        $chauffeurs = $query->get();

        return Excel::download(new class($chauffeurs) implements FromCollection, WithHeadings, WithMapping, WithStyles {
            private $chauffeurs;

            public function __construct($chauffeurs)
            {
                $this->chauffeurs = $chauffeurs;
            }

            public function collection()
            {
                return $this->chauffeurs;
            }

            public function headings(): array
            {
                return [
                    'Nom',
                    'Prénom',
                    'Email',
                    'Téléphone',
                    'Adresse',
                    'Date de naissance',
                    'Date d\'embauche',
                    'Numéro de permis',
                    'Expiration du permis',
                    'Statut',
                    'Date de création'
                ];
            }

            public function map($chauffeur): array
            {
                return [
                    $chauffeur->nom,
                    $chauffeur->prenom,
                    $chauffeur->email,
                    $chauffeur->tel,
                    $chauffeur->adresse,
                    $chauffeur->date_naissance,
                    $chauffeur->date_embauche,
                    $chauffeur->numero_permis,
                    $chauffeur->permis_expire_le,
                    ucfirst($chauffeur->statut),
                    $chauffeur->created_at->format('d/m/Y H:i')
                ];
            }

            public function styles(Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }
        }, 'chauffeurs_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public static function exportAffectations($filters = [])
    {
        $query = Affectation::with(['chauffeur', 'vehicule.marque', 'vehicule.modele'])
            ->whereHas('chauffeur', function ($query) {
                $query->where('admin_id', Auth::user()->user_id);
            });

        // Appliquer les filtres
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['chauffeur_id'])) {
            $query->where('chauffeur_id', $filters['chauffeur_id']);
        }

        $affectations = $query->get();

        return Excel::download(new class($affectations) implements FromCollection, WithHeadings, WithMapping, WithStyles {
            private $affectations;

            public function __construct($affectations)
            {
                $this->affectations = $affectations;
            }

            public function collection()
            {
                return $this->affectations;
            }

            public function headings(): array
            {
                return [
                    'Chauffeur',
                    'Véhicule',
                    'Immatriculation',
                    'Date de début',
                    'Date de fin',
                    'Statut',
                    'Description',
                    'Date de création'
                ];
            }

            public function map($affectation): array
            {
                return [
                    $affectation->chauffeur->nom . ' ' . $affectation->chauffeur->prenom,
                    ($affectation->vehicule->marque->nom ?? 'N/A') . ' ' . ($affectation->vehicule->modele->nom ?? 'N/A'),
                    $affectation->vehicule->immatriculation,
                    $affectation->date_debut,
                    $affectation->date_fin,
                    ucfirst($affectation->status),
                    $affectation->description,
                    $affectation->created_at->format('d/m/Y H:i')
                ];
            }

            public function styles(Worksheet $sheet)
            {
                return [
                    1 => ['font' => ['bold' => true]],
                ];
            }
        }, 'affectations_' . date('Y-m-d_H-i-s') . '.xlsx');
    }
}
