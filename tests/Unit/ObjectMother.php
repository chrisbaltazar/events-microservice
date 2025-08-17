<?php

namespace Tests\Unit;

use Faker\Factory;
use Faker\Generator;

abstract class ObjectMother
{
    private static Generator $faker;

    protected static function faker(): Generator
    {
        if (!isset(self::$faker)) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
}
