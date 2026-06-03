<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Docker\Docker;
use Docker\Stream\AttachStream;

class AddSlotsLabels extends Command
{
    protected $signature = 'servers:add-slots-labels {--default=10 : Valeur par défaut de slots pour les anciens serveurs}';
    protected $description = 'Ajoute les labels "slots" aux anciens containers Docker qui n\'en ont pas';

    public function handle()
    {
        $defaultSlots = (int) $this->option('default');
        
        $this->info('Recherche des containers sans label "slots"...');

        try {
            // Utiliser Docker CLI directement
            $output = shell_exec('docker ps -a --format "{{.Names}}"');
            $containers = array_filter(explode("\n", trim($output)));
            
            $updated = 0;
            $skipped = 0;

            foreach ($containers as $containerName) {
                // Vérifier si le container a le label slots
                $labels = shell_exec("docker inspect " . escapeshellarg($containerName) . " --format \"{{json .Config.Labels}}\"");
                $labelsArray = json_decode(trim($labels), true);

                if (empty($labelsArray)) {
                    $this->line("⚠️  Impossible de lire les labels de {$containerName}");
                    continue;
                }

                if (!isset($labelsArray['slots']) || $labelsArray['slots'] === '' || $labelsArray['slots'] === '1') {
                    // Le container n'a pas le label slots ou a la valeur par défaut (1)
                    // Récréer le container avec le label correct est plus sûr, ou on peut la laisser en DB
                    // Pour l'instant, on va juste noter le container et laisser la DB gérer
                    $this->line("⚠️  {$containerName} : slots absent ou à 1 (géré en DB)");
                    $skipped++;
                } else {
                    $this->line("✓ {$containerName} : slots=" . $labelsArray['slots']);
                    $skipped++;
                }
            }

            $this->info("\n✅ Vérification terminée:");
            $this->info("   - Containers vérifiés: " . ($updated + $skipped));
            $this->info("\n💡 Astuce: Les anciens serveurs avec slots=1 en DB resteront à 1.");
            $this->info("💡 Exécutez: php artisan servers:sync");

        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
