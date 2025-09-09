<?php

namespace App\Exports;

use App\Models\Tache;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TachesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        protected int $chauffeurId,
        protected int $year,
        protected int $month
    ) {}

    public function collection()
    {
        return Tache::with(['vehicule'])
            ->where('chauffeur_id', $this->chauffeurId)
            ->whereYear('start_date', $this->year)
            ->whereMonth('start_date', $this->month)
            ->orderByDesc('start_date')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID', 'Véhicule', 'Début', 'Fin', 'Statut', 'Validée', 'Lat début', 'Lon début', 'Lat fin', 'Lon fin',
        ];
    }

    public function map($tache): array
    {
        return [
            $tache->id,
            optional($tache->vehicule)->immatriculation,
            optional($tache->start_date)?->format('Y-m-d H:i:s'),
            optional($tache->end_date)?->format('Y-m-d H:i:s'),
            $tache->status,
            $tache->is_validated ? 'Oui' : 'Non',
            $tache->start_latitude,
            $tache->start_longitude,
            $tache->end_latitude,
            $tache->end_longitude,
        ];
    }
}



