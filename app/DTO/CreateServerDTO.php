<?php

namespace App\DTO;

class CreateServerDTO
{
    public string $name;
    public int $slots;
    public int $players;
    public string $image;

    public function __construct(string $name, int $slots, string $image = 'server-vintagestory:latest')
    {
        $this->name = $name;
        $this->slots = $slots;
        $this->players = $slots;
        $this->image = $image;
    }
}
