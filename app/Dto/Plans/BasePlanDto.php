<?php

namespace App\Dto\Plans;

use Illuminate\Support\Collection;

readonly class BasePlanDto
{
    use ArrayableObject;

    public function __construct(
        public int $id,
        public string $title,
        public string $sellMode,
        public Collection $plans
    ) {
    }
}
