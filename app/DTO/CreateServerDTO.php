<?php

namespace App\DTO;

class CreateServerDTO
{
    public string $name;
    public int $slots;

    public function __construct(string $name, int $slots)
    {
        $this->name = $name;
        $this->slots = $slots;
    }
}
