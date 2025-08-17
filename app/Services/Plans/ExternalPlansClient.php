<?php

namespace App\Services\Plans;

use App\Exceptions\Plans\ExternalPlansHttpException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use UnexpectedValueException;

readonly class ExternalPlansClient implements ExternalPlansClientInterface
{
    public function __construct(
        private string $url,
    ) {
    }

    /**
     * @throws ExternalPlansHttpException
     * @throws ConnectionException
     */
    public function fetch(): string
    {
        if (empty($this->url)) {
            throw new UnexpectedValueException('Plan provider url not set');
        }

        $url = sprintf('%s/api/events', trim($this->url, '/'));
        $response = Http::timeout(10)->get($url);

        if (!$response->successful()) {
            throw new ExternalPlansHttpException($response);
        }

        return $response->body();
    }
}
