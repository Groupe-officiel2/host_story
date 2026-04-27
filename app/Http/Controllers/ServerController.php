<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use App\Services\GoApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServerController extends Controller
{
    public function index()
    {

        $servers = [
            new ServerDTO(1, "Pokemon", 4, 5),
            new ServerDTO(2, "Survival", 2, 10),
            new ServerDTO(1, "Minecraft", 6, 20),
            new ServerDTO(2, "Arc", 3, 10),
            new ServerDTO(1, "GTA", 30, 50),
        ];

        $exampleDTO = new CreateServerDTO("Test", 5);

        return view('servers.index', compact('servers'));
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
