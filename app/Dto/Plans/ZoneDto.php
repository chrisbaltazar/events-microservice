<?php

namespace App\Dto\Plans;

readonly class ZoneDto
{
    use ArrayableObject;

    public function __construct(
        public int $id,
        public int $planId,
        public string $name,
        public float $price,
    ) {
    }
}
