<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use App\Models\Server;

class ServerController extends Controller
{
    public function index()
    {
        $client = new Client([
            'base_uri' => 'http://172.26.162.80:8082',
            'timeout'  => 5.0,
        ]);

        try {
            $response = $client->get('/servers', [
                'headers' => [
                    'Authorization' => 'Bearer ' . \App\Services\JwtService::generateToken(auth()->id())
                ]
            ]);
        } catch (\Exception $e) {
            dd('API ERROR: ' . $e->getMessage());
        }

        $data = json_decode($response->getBody(), true);

        if (!is_array($data)) {
            abort(500, 'Réponse API invalide');
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

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'slots' => 'required|integer|min:1'
        ]);

        $response = Http::post('http://host.docker.internal:8082/create-server', [
            'name' => $request->name,
            'slots' => $request->slots,
        ]);

        if (!$response->successful()) {
            return back()->withErrors('Erreur création serveur Go');
        }

        $data = $response->json();

        $server = Server::create([
            'name' => $data['name'],
            'slots' => $data['slots'],
            'port' => $data['port'] ?? null,
            'container_id' => $data['container_id'] ?? null,
        ]);

        $server->users()->attach(auth()->id(), [
            'role' => 'owner'
        ]);

        return redirect()->back();
    }
}
