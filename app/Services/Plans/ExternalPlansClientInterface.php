<?php

namespace App\Services\Plans;

interface ExternalPlansClientInterface
{
    public function fetch(): string;
}
