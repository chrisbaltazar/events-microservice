<?php

namespace App\Repositories;

use App\Models\BasePlan;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use UnexpectedValueException;

class BasePlanRepository implements DatabaseRepositoryInterface
{
    public function save(array $data): mixed
    {
        $where = array_filter(['id' => $data['id'] ?? null, 'external_id' => $data['external_id'] ?? null]);
        if (empty($where)) {
            throw new UnexpectedValueException('Insufficient data to save BasePlan');
        }

        return BasePlan::updateOrCreate($where, [
            'title' => $data['title'],
            'external_id' => $data['external_id'],
            'uuid' => $data['uuid'] ?? Str::uuid(),
        ]);
    }
}
