<?php

namespace App\Repositories;

interface DatabaseRepositoryInterface
{
    public function save(array $data): mixed;
}
