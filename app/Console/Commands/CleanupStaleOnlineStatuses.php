<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OnlineStatusService;

class CleanupStaleOnlineStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'online-status:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les statuts online périmés (plus de 30 secondes sans heartbeat)';

    protected $onlineStatusService;

    public function __construct(OnlineStatusService $onlineStatusService)
    {
        parent::__construct();
        $this->onlineStatusService = $onlineStatusService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Nettoyage des statuts online périmés...');

        $updatedCount = $this->onlineStatusService->cleanupStaleStatuses();

        if ($updatedCount > 0) {
            $this->info("✅ {$updatedCount} utilisateur(s) marqué(s) comme offline");
        } else {
            $this->info('✅ Aucun statut périmé trouvé');
        }

        return Command::SUCCESS;
    }
}
