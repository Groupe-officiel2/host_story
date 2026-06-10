<?php

namespace App\Http\Controllers;

use App\Models\Server;
use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;


class ServerController extends Controller
{
    public function index()
    {
        $servers = Server::all(); // récupère tous les serveurs en BDD

        return view('servers.index', compact('servers'));
    }

    public function toggle(Request $request)
    {
        $name = $request->input('name');

        // Générer le token JWT
        $token = $this->generateJwt();

        // Appel HTTP avec le JWT
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("http://192.168.149.144:8082/toggle?name={$name}");

        return response($response->body());
    }

    public function status(Request $request)
    {
        $name = $request->input('name');
        
        // Générer le token JWT
        $token = $this->generateJwt();

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->timeout(5)->get("http://192.168.149.144:8082/status?name={$name}");

            return $response->body();
        } catch (\Exception $e) {
            return 'stopped';
        }
    }

    private function generateJwt(): string
    {
        $userId = Auth::check() ? (string) Auth::id() : 'test_user';
        $secretKey = env('JWT_SECRET', 'secret');
        
        $issuedAt = time();
        $expire = $issuedAt + (24 * 60 * 60); // 24 heures

        $payload = [
            'iat'  => $issuedAt,
            'exp'  => $expire,
            'sub'  => $userId,
            'role' => 'admin',
        ];

        return JWT::encode($payload, $secretKey, 'HS256');
    }










    public function show($id)
    {
        $server = Server::findOrFail($id);

        return view('servers.show', compact('server'));
    }
}


