<?php

namespace App\Exceptions\Plans;

use Exception;
use Illuminate\Http\Client\Response;

class ExternalPlansHttpException extends Exception
{
    public function __construct(Response $response)
    {
        parent::__construct("[PlansProviderClient]: " . $response->getReasonPhrase(), $response->getStatusCode());
    }
}
