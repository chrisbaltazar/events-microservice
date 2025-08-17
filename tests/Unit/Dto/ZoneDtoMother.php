<?php

namespace Tests\Unit\Dto;

use App\Dto\Plans\ZoneDto;
use Tests\Unit\ObjectMother;

class ZoneDtoMother extends ObjectMother
{
    public static function random(array $data = []): ZoneDto
    {
        return new ZoneDto(... array_merge([
            'id' => self::faker()->randomNumber(),
            'planId' => self::faker()->randomNumber(),
            'name' => self::faker()->name(),
            'price' => self::faker()->randomNumber(),
        ], $data));
    }
}
