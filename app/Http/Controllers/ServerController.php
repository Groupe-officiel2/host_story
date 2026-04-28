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
        $client = new Client([
            'base_uri' => 'http://host.docker.internal:8082',
            'timeout'  => 5.0,
        ]);

        try {
            $response = $client->get('/servers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . \App\Services\JwtService::generateToken(auth()->id())
                ]
            ]);
            $body = (string) $response->getBody();
            $data = json_decode($body, true);
        } catch (\Exception $e) {
            // Si l'API Go n'est pas allumé, on ne crash plus la page.
            $data = [];
        }

        if (!is_array($data)) {
            $data = [];
        }

        $servers = [];

        foreach ($data as $s) {
            $servers[] = new ServerDTO(
                $s['ID'] ?? null,
                $s['Name'] ?? null,
                $s['Players'] ?? 0,
                $s['Slots'] ?? 0
            );
        }

        return view('servers.index', compact('servers'));
    }

    public function data()
    {
        $client = new Client([
            'base_uri' => 'http://host.docker.internal:8082',
            'timeout'  => 5.0,
        ]);

        try {
            $response = $client->get('/servers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('jwt_token')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'API unreachable',
                'message' => $e->getMessage()
            ], 500);
        }

        $body = (string) $response->getBody();
        $data = json_decode($body, true);

        if (!is_array($data)) {
            return response()->json([
                'error' => 'Invalid API response',
                'raw' => $body
            ], 500);
        }

        return response()->json(array_map(function ($s) {
            return [
                'id' => $s['ID'] ?? null,
                'name' => $s['Name'] ?? null,
                'players' => $s['Players'] ?? 0,
                'slots' => $s['Slots'] ?? 0,
            ];
        }, $data));    }

    public function storeFromGo(Request $request)
    {
        if ($request->header('X-API-KEY') !== 'your_very_long_secret_key_123456789') {
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
}
