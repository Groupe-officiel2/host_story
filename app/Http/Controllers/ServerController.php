<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;

class ServerController extends Controller
{
    public function index()
    {
        $servers = [
            new ServerDTO(1, "Pokemon", 4, 5),
            new ServerDTO(2, "Survival", 2, 10),
        ];

        return view('servers.index', compact('servers'));
    }
}
