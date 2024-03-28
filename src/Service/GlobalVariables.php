<?php

namespace App\Service;

class GlobalVariables
{
    const MAX_CATTLE_PER_HECTARE = 18;

    public function max_cattle_per_hectare(): int
    {
        return self::MAX_CATTLE_PER_HECTARE;
    }
}