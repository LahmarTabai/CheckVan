<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\VehiculeApiService;

class SyncVehiculeData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicules:sync {--marques : Synchroniser seulement les marques} {--modeles : Synchroniser seulement les modèles} {--clear-cache : Vider le cache avant synchronisation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise les données de marques et modèles depuis les APIs externes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiService = new VehiculeApiService();
        
        if ($this->option('clear-cache')) {
            $this->info('Vidage du cache...');
            $apiService->clearCache();
        }
        
        if ($this->option('marques') || !$this->option('modeles')) {
            $this->info('Synchronisation des marques...');
            $count = $apiService->syncMarquesFromApi();
            $this->info("✅ {$count} marques synchronisées");
        }
        
        if ($this->option('modeles') || !$this->option('marques')) {
            $this->info('Synchronisation des modèles...');
            $marques = \App\Models\Marque::where('is_active', true)->get();
            $totalModeles = 0;
            
            $progressBar = $this->output->createProgressBar($marques->count());
            $progressBar->start();
            
            foreach ($marques as $marque) {
                $count = $apiService->syncModelesFromApi($marque->id);
                $totalModeles += $count;
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
            $this->info("✅ {$totalModeles} modèles synchronisés pour {$marques->count()} marques");
        }
        
        $this->info('🎉 Synchronisation terminée !');
    }
}