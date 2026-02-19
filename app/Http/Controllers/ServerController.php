<?php

namespace App\Http\Controllers;
use App\DTO\CreateServerDTO;

use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = [];

        $exampleDTO = new CreateServerDTO("Test", 5);

        return view('servers.index', compact('servers'));
    }
}
