<?php

namespace App\DTO;

class ServerDTO
{
    public int $id;
    public string $name;
    public int $players;
    public int $slots;

    public function __construct(int $id, string $name, int $players, int $slots)
    {
        $this->id = $id;
        $this->name = $name;
        $this->players = $players;
        $this->slots = $slots;
    }
}
