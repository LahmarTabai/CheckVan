<?php

namespace App\Http\Controllers;

use App\Exports\TachesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function tachesParChauffeur(Request $request)
    {
        $request->validate([
            'chauffeur_id' => 'required|integer',
            'year' => 'required|integer|min:2000',
            'month' => 'required|integer|min:1|max:12',
        ]);

        // Sécurité: vérifier que le chauffeur appartient à l'admin connecté
        $chauffeurId = (int) $request->input('chauffeur_id');
        $year = (int) $request->input('year');
        $month = (int) $request->input('month');

        $isOwned = \App\Models\User::where('id', $chauffeurId)
            ->where('role', 'chauffeur')
            ->where('admin_id', Auth::id())
            ->exists();

        abort_unless($isOwned, 403);

        $fileName = sprintf('taches_chauffeur_%d_%04d-%02d.xlsx', $chauffeurId, $year, $month);

        return Excel::download(new TachesExport($chauffeurId, $year, $month), $fileName);
    }
}



