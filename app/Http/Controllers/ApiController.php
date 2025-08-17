<?php

namespace App\Http\Controllers;

use App\Services\UseCases\PlansDateSearchAction;
use Carbon\Exceptions\InvalidFormatException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use RuntimeException;
use UnexpectedValueException;

class ApiController extends Controller
{
    public const MAX_PAGINATION = 100;

    public function search(Request $request, PlansDateSearchAction $searchAction)
    {
        try {
            $this->validateRequestData($request);

            $data = $searchAction($request);

            if (empty($data)) {
                throw new RuntimeException('Content not found', 404);
            }

            return response()->json(['data' => $data, 'error' => null]);
        } catch (Exception $e) {
            return response()->json([
                'error' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                ],
            ], $e->getCode());
        }
    }

    private function validateRequestData(Request $request): void
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        if (empty($startDate) || empty($endDate)) {
            throw new UnexpectedValueException('Insufficient data to process request', 400);
        }

        try {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        } catch (InvalidFormatException) {
            throw new UnexpectedValueException('Invalid date format', 400);
        }

        if ($startDate->greaterThanOrEqualTo($endDate)) {
            throw new UnexpectedValueException('End date must be greater than start date', 400);
        }
    }
}
