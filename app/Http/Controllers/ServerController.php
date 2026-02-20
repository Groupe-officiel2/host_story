<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        // Simulation serveurs existants (Story 4)
        $servers = [
            new ServerDTO(1, "Pokemon", 4, 5),
            new ServerDTO(2, "Survival", 2, 10),
        ];

        // Simulation DTO création (Story 3.2)
        // (sert juste à montrer que l’objet existe dans l’archi)
        $exampleDTO = new CreateServerDTO("Test", 5);

        return view('servers.index', compact('servers'));
    }
}
