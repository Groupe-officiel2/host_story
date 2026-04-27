<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServerController extends Controller
{
    public function index()
    {
        

        $servers = [
            new ServerDTO(1, "Pokemon", 4, 5),
            new ServerDTO(2, "Survival", 2, 10),
            new ServerDTO(3, "Minecraft", 6, 20),
            new ServerDTO(4, "Arc", 3, 10),
            new ServerDTO(5, "example", 30, 50),
        ];

        $exampleDTO = new CreateServerDTO("Test", 5);

        return view('servers.index', compact('servers'));
    }

    public function toggle(Request $request)
    {
        $name = $request->input('name');

        // Récupérer le token depuis la session
        $token = session('token');

        

        if (!$token) {
            return back()->withErrors(['token' => 'Token not found. Please login again.']);
        }

        // Appel HTTP avec le JWT
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post("http://192.168.149.144:8082/toggle?name={$name}");

        return response($response->body());
    }

    public function status(Request $request)
    {
        $name = $request->input('name');
        $token = session('token');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get("http://192.168.149.144:8082/status?name={$name}");

        return $response->body(); // "running" ou "stopped"
    }

}
