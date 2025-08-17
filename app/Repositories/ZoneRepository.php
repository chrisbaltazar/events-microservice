<?php

namespace App\Repositories;

use App\Models\Zone;
use http\Exception\UnexpectedValueException;

class ZoneRepository implements DatabaseRepositoryInterface
{

    public function save(array $data): mixed
    {
        $where = array_filter([
            'id' => $data['id'] ?? null,
            'plan_id' => $data['plan_id'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'external_plan_id' => $data['external_plan_id'] ?? null,
        ]);
//        dump($where);
        if (empty($where)) {
            throw new UnexpectedValueException('Insufficient data to save Zone');
        }

        return Zone::updateOrCreate($where, [
            'plan_id' => $data['plan_id'],
            'external_id' => $data['external_id'],
            'external_plan_id' => $data['external_plan_id'],
            'name' => $data['name'],
            'price' => $data['price'],
        ]);
    }
}
