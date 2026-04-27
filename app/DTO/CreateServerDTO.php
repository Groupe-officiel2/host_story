<?php

namespace App\DTO;

class CreateServerDTO
{
    public string $name;
    public int $players;
    public string $image;

    public function __construct(string $name, int $players, string $image = 'server-vintagestory:latest')
    {
        $this->name = $name;
        $this->players = $players;
        $this->image = $image;
    }
}
