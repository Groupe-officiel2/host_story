<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $servers = [];

        return view('servers.index', compact('servers'));
    }
}
