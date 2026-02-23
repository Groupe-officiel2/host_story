<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = [
            new ServerDTO(1, "Pokemon", 4, 5),
            new ServerDTO(2, "Survival", 2, 10),
            new ServerDTO(1, "Minecraft", 6, 20),
            new ServerDTO(2, "Arc", 3, 10),
            new ServerDTO(1, "GAT", 30, 50),
        ];

        $exampleDTO = new CreateServerDTO("Test", 5);

        return view('servers.index', compact('servers'));
    }
}
