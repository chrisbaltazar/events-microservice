<?php

namespace Tests\Unit\Dto;

use App\Dto\Plans\PlanDto;
use Tests\Unit\ObjectMother;

class PlanDtoMother extends ObjectMother
{

    public static function random(array $data = []): PlanDto
    {
        return new PlanDto(... array_merge([
            'id' => self::faker()->randomNumber(),
            'basePlanId' => self::faker()->randomNumber(),
            'startDate' => self::faker()->dateTime(),
            'endDate' => self::faker()->dateTime(),
            'zones' => collect(self::faker()->randomElements()),
        ], $data));
    }
}
