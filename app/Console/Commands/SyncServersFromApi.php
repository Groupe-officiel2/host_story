<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;

class SyncServersFromApi extends Command
{
    protected $signature = 'servers:sync';
    protected $description = 'Synchronise les serveurs depuis le Go API';

    public function handle()
    {
        $this->info('Synchronisation des serveurs depuis le Go API...');

        $client = new Client([
            'base_uri' => 'http://hoststory-api:8082',
            'timeout' => 5.0,
        ]);

        try {
            $response = $client->get('/servers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . \App\Services\JwtService::generateToken('system')
                ]
            ]);
            
            $body = json_decode((string) $response->getBody(), true);
            
            if (!is_array($body)) {
                $this->error('Réponse invalide du Go API');
                return 1;
            }

            $liveData = collect($body)->keyBy('Name')->toArray();

            // Suppression des serveurs qui n'existent plus sur Docker
            $dbServers = \App\Models\Server::all();
            foreach ($dbServers as $s) {
                if (!isset($liveData[$s->name])) {
                    $this->line("🗑️  Suppression: {$s->name}");
                    $s->delete();
                }
            }

            // Import ou mise à jour des serveurs
            $updated = 0;
            foreach ($liveData as $live) {
                $existingServer = \App\Models\Server::where('name', $live['Name'])->first();

                if ($existingServer && isset($live['Slots']) && $live['Slots'] == 1) {
                    $finalSlots = $existingServer->slots;
                } else {
                    $finalSlots = $live['Slots'] ?? 10;
                }

                $server = \App\Models\Server::updateOrCreate(
                    ['name' => $live['Name']],
                    ['slots' => $finalSlots]
                );

                \App\Models\Server::where('name', $live['Name'])
                    ->where('id', '!=', $server->id)
                    ->delete();

                if ($existingServer && $existingServer->slots != $finalSlots) {
                    $this->line("📝 Mise à jour: {$live['Name']} (slots: {$existingServer->slots} → {$finalSlots})");
                    $updated++;
                } else {
                    $this->line("✓ {$live['Name']} (slots: {$finalSlots})");
                }
            }

            $this->info("\n✅ Synchronisation terminée ({$updated} mises à jour)");
            return 0;

        } catch (\Exception $e) {
            $this->error('Erreur: ' . $e->getMessage());
            return 1;
        }
    }
}
