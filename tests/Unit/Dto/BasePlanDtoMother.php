<?php

namespace Tests\Unit\Dto;

use App\Dto\Plans\BasePlanDto;
use Tests\Unit\ObjectMother;

class BasePlanDtoMother extends ObjectMother
{

    public static function random(array $data = []): BasePlanDto
    {
        return new BasePlanDto(... array_merge([
            'id' => self::faker()->randomNumber(),
            'title' => self::faker()->title(),
            'sellMode' => self::faker()->randomElement(['online', 'offline']),
            'plans' => collect(self::faker()->randomElements()),
        ], $data));
    }

}
