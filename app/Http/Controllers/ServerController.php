<?php
namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use App\Services\GoApiService;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Server;
use Illuminate\Support\Facades\Auth;

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
                
                // Import ou mise à jour des serveurs
                foreach ($liveData as $live) {
                    $existingServer = \App\Models\Server::where('name', $live['Name'])->first();
                    
                    // Pour les NOUVEAUX serveurs : utiliser slots de Go API
                    // Pour les EXISTANTS : garder leurs slots actuels (fonctionne avec anciens serveurs)
                    $updateData = [];
                    if (!$existingServer) {
                        $updateData['slots'] = $live['Slots'] ?? 10;
                    }

                    $server = \App\Models\Server::updateOrCreate(
                        ['name' => $live['Name']],
                        $updateData
                    );

                    // Conserver une seule ligne par nom en cas de doublons existants
                    \App\Models\Server::where('name', $live['Name'])
                        ->where('id', '!=', $server->id)
                        ->delete();
                }
            }
        } catch (\Exception $e) {
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
                
                // Import ou mise à jour des serveurs
                foreach ($liveData as $live) {
                    $existingServer = \App\Models\Server::where('name', $live['Name'])->first();
                    
                    // Pour les NOUVEAUX serveurs : utiliser slots de Go API
                    // Pour les EXISTANTS : garder leurs slots actuels (fonctionne avec anciens serveurs)
                    $updateData = [];
                    if (!$existingServer) {
                        $updateData['slots'] = $live['Slots'] ?? 10;
                    }

                    $server = \App\Models\Server::updateOrCreate(
                        ['name' => $live['Name']],
                        $updateData
                    );

                    \App\Models\Server::where('name', $live['Name'])
                        ->where('id', '!=', $server->id)
                        ->delete();
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
        $server = Server::where('name', $request->name)->first();
        if ($server) {
            $server->slots = $request->slots;
            $server->save();
        } else {
            $server = Server::create([
                'id' => $request->id ?? \Illuminate\Support\Str::uuid()->toString(),
                'name' => $request->name,
                'slots' => $request->slots,
            ]);
        }
        return response()->json(['status' => 'created', 'server' => $server]);
    }

    public function store(Request $request, GoApiService $goApiService)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:servers,name',
            'slots' => 'required|integer|min:1|max:50',
        ]);
        $userId = Auth::check() ? (string) Auth::id() : null;
        $dto = new CreateServerDTO(
            $request->input('name'),
            $request->input('slots'),
            'server-vintagestory:latest'
        );
        try {
            $result = $goApiService->createServer($dto, $userId);
            if (!is_array($result) || !isset($result['id'])) {
                return redirect()->back()->with('error', 'Impossible de créer le serveur : réponse API invalide.');
            }
            return redirect()->route('dashboard')->with('server_success', 'Serveur en cours de création !');
        } catch (\Exception $e) {
            \Log::error('ServerController store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Impossible de contacter l\'API de création.');
        }
    }
}