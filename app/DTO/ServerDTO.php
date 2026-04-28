<?php

namespace App\DTO;

class ServerDTO
{
    public string $id;
    public string $name;
    public int $players;
    public int $slots;
    public ?int $port;
    public string $status;

    public function __construct(string $id, string $name, int $players, int $slots, ?int $port = null, string $status = 'offline')
    {
        $this->id = $id;
        $this->name = $name;
        $this->players = $players;
        $this->slots = $slots;
        $this->port = $port;
        $this->status = $status;
    }
}
