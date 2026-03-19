<?php

namespace App\Http\Controllers;

use App\DTO\ServerDTO;
use App\DTO\CreateServerDTO;
use Illuminate\Http\Request;

class ServerController extends Controller
{
    public function index()
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => 'http://localhost:8080'
        ]);

        $response = $client->request('GET', '/servers', [
            'headers' => [
                'Authorization' => 'Bearer TON_TOKEN'
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        $servers = [];

        foreach ($data as $s) {
            $servers[] = new ServerDTO(
                $s['ID'],
                $s['Name'],
                $s['Players'],
                $s['Slots']
            );
        }

        return view('servers.index', compact('servers'));
    }
}
