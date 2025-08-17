<?php

namespace App\Dto\Plans;

use Illuminate\Support\Str;

trait ArrayableObject
{
    public function asArray(): array
    {
        return collect(get_object_vars($this))->mapWithKeys(function ($value, $key) {
            return [Str::snake($key) => $value];
        })->all();
    }
}
