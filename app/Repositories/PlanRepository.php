<?php

namespace App\Repositories;

use App\Models\Plan;
use DateTimeInterface;
use Illuminate\Support\Collection;
use UnexpectedValueException;

class PlanRepository implements DatabaseRepositoryInterface
{

    public function save(array $data): mixed
    {
        $where = array_filter([
            'id' => $data['id'] ?? null,
            'base_plan_id' => $data['base_plan_id'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'external_base_id' => $data['external_base_id'] ?? null,
        ]);

        if (empty($where)) {
            throw new UnexpectedValueException('Insufficient data to save Plan');
        }

        return Plan::updateOrCreate($where, [
            'external_id' => $data['external_id'],
            'external_base_id' => $data['external_base_id'],
            'base_plan_id' => $data['base_plan_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
        ]);
    }

    public function findBetween(
        DateTimeInterface $start,
        DateTimeInterface $end,
        int $page = 1,
        int $pagination = 100,
    ): Collection {
        return Plan::with('base')
            ->whereDate('start_date', '>=', $start->format('Y-m-d'))
            ->whereDate('end_date', '<=', $end->format('Y-m-d'))
            ->forPage($page, $pagination)
            ->get();
    }
}
