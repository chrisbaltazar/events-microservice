<?php

namespace App\Services\Plans;

interface ExternalPlansParserInterface
{
    public function parse(string $content): array;
}
