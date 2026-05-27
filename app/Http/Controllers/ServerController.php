<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use App\Services\GoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Server;
use Illuminate\Support\Facades\Auth;
use App\Services\PayPalService;
use App\Models\Subscription;

class ServerController extends Controller
{
    public function index()
    {
        $liveData = [];
        $client = new Client([
            'base_uri' => 'http://hoststory-api:8082',
            'timeout' => 5.0,
        ]);

        try {
            $response = $client->get('/servers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . \App\Services\JwtService::generateToken(auth()->id())
                ]
            ]);
            $body = json_decode((string) $response->getBody(), true);
            if (is_array($body)) {
                $liveData = collect($body)->keyBy('Name')->toArray();

                // Suppression des serveurs qui n'existent plus sur Docker
                $dbServers = \App\Models\Server::all();
                foreach ($dbServers as $s) {
                    if (!isset($liveData[$s->name])) {
                        $s->delete();
                    }
                }

                // Import des serveurs Docker vers la BDD Laravel si pas existants
                foreach ($liveData as $live) {
                    \App\Models\Server::firstOrCreate(
                        ['name' => $live['Name']],
                        [
                            'id' => \Illuminate\Support\Str::uuid()->toString(),
                            'slots' => $live['Slots'] ?? 10
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            // Ignore si API hors ligne
            \Log::error("Erreur API Go : " . $e->getMessage());
        }

        $dbServers = \App\Models\Server::all();
        $servers = [];
        foreach ($dbServers as $s) {
            $live = $liveData[$s->name] ?? null;
            $servers[] = new ServerDTO(
                $s->id,
                $s->name,
                $live ? ($live['Players'] ?? 0) : 0,
                $s->slots,
                $live ? ($live['Port'] ?? null) : null,
                ($live && isset($live['State']) && strtolower($live['State']) === 'running') ? 'online' : 'offline'
            );
        }

        return view('servers.index', compact('servers'));
    }

    public function data()
    {
        $liveData = [];
        $client = new Client([
            'base_uri' => 'http://hoststory-api:8082',
            'timeout' => 5.0,
        ]);

        try {
            $response = $client->get('/servers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . \App\Services\JwtService::generateToken(auth()->id())
                ]
            ]);
            $body = json_decode((string) $response->getBody(), true);
            if (is_array($body)) {
                $liveData = collect($body)->keyBy('Name')->toArray();
                
                // Suppression des serveurs qui n'existent plus sur Docker
                $dbServers = \App\Models\Server::all();
                foreach ($dbServers as $s) {
                    if (!isset($liveData[$s->name])) {
                        $s->delete();
                    }
                }

                // Import
                foreach ($liveData as $live) {
                    \App\Models\Server::firstOrCreate(
                        ['name' => $live['Name']],
                        [
                            'id' => \Illuminate\Support\Str::uuid()->toString(),
                            'slots' => $live['Slots'] ?? 10
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            // Ignore
        }

        $dbServers = \App\Models\Server::all();
        return response()->json($dbServers->map(function ($s) use ($liveData) {
            $live = $liveData[$s->name] ?? null;
            return [
                'id' => $s->id,
                'name' => $s->name,
                'players' => $live ? ($live['Players'] ?? 0) : 0,
                'slots' => $s->slots,
                'port' => $live ? ($live['Port'] ?? null) : null,
                'status' => ($live && isset($live['State']) && strtolower($live['State']) === 'running') ? 'online' : 'offline'
            ];
        }));
    }

    public function storeFromGo(Request $request)
    {
        if ($request->header('X-API-KEY') !== 'SECRET123' && $request->header('X-API-KEY') !== 'your_very_long_secret_key_123456789') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $server = \App\Models\Server::updateOrCreate(
            ['name' => $request->name],
            [
                'id' => $request->id,
                'slots' => $request->slots,
            ]
        );

        return response()->json([
            'status' => 'created',
            'server' => $server
        ]);
    }

    public function store(Request $request, GoApiService $goApiService)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slots' => 'required|integer|min:1|max:50',
        ]);

        $userId = Auth::check() ? (string) Auth::id() : null;

        $dto = new CreateServerDTO(
            $request->input('name'),
            $request->input('slots'),
            'server-vintagestory:latest'
        );

        try {
            $goApiService->createServer($dto, $userId);

            return redirect()->route('dashboard')
                ->with('server_success', 'Serveur en cours de création !');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Impossible de contacter l\'API de création.');
        }
    }
}