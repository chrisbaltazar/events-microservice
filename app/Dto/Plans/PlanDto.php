<?php

namespace App\Dto\Plans;

use DateTimeInterface;
use Illuminate\Support\Collection;

readonly class PlanDto
{
    use ArrayableObject;

    public function __construct(
        public int $id,
        public int $basePlanId,
        public DateTimeInterface $startDate,
        public DateTimeInterface $endDate,
        public Collection $zones,
    ) {
    }
}
