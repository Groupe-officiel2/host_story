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

class ServerController extends Controller
{
    public function index()
    {
        $liveData = [];
        $client = new Client([
            'base_uri' => 'http://host.docker.internal:8082',
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
                $live ? 'online' : 'offline'
            );
        }

        return view('servers.index', compact('servers'));
    }

    public function data()
    {
        $liveData = [];
        $client = new Client([
            'base_uri' => 'http://host.docker.internal:8082',
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
                'status' => $live ? 'online' : 'offline'
            ];
        }));
    }

    public function storeFromGo(Request $request)
    {
        if ($request->header('X-API-KEY') !== 'SECRET123' && $request->header('X-API-KEY') !== 'your_very_long_secret_key_123456789') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $server = \App\Models\Server::create([
            'id' => $request->id,
            'name' => $request->name,
            'slots' => $request->slots,
        ]);

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

        $dto = new CreateServerDTO(
            $request->input('name'),
            $request->input('slots'),
            'server-vintagestory:latest'
        );

        // Utilisateur connecté, ou 'test_user' par défaut
        $userId = Auth::check() ? (string) Auth::id() : 'test_user';

        // Appel à l'API Go
        $response = $goApiService->createServer($dto, $userId);

        return redirect()->route('dashboard')->with('server_success', 'Serveur en cours de création !');
    }
}
