<?php

namespace App\Services\UseCases;

use App\Models\Plan;
use App\Repositories\PlanRepository;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PlansDateSearchAction
{
    public const DEFAULT_PAGINATION = 100;

    public function __construct(private PlanRepository $planRepository)
    {
    }

    public function __invoke(Request $request): array
    {
        $startDate = new DateTimeImmutable($request->query('start_date'));
        $endDate = new DateTimeImmutable($request->query('end_date'));
        $page = (int) $request->query('page', 1);
        $pagination = (int) $request->query('pagination', self::DEFAULT_PAGINATION);
        $pagination = min($pagination, self::DEFAULT_PAGINATION);

        $cacheKey = $this->getCacheKey($startDate, $endDate, $page, $pagination);
        $plans = $this->getFromCache($cacheKey);
        if (!$plans) {
            $plans = $this->planRepository->findBetween($startDate, $endDate, $page, $pagination);
            $this->storeInCache($cacheKey, $plans);
        }

        if ($plans->isEmpty()) {
            return [];
        }

        return $this->toArray($plans);
    }

    private function toArray(Collection $plans): array
    {
        return [
            'events' => $plans->map(function (Plan $plan) {
                return [
                    'id' => $plan->base->uuid,
                    'title' => $plan->base->title,
                    'start_date' => $plan->start_date->toDateString(),
                    'start_time' => $plan->start_date->toTimeString(),
                    'end_date' => $plan->end_date->toDateString(),
                    'end_time' => $plan->end_date->toTimeString(),
                    'min_price' => $plan->min_price,
                    'max_price' => $plan->max_price,
                ];
            }),
        ];
    }

    private function getCacheKey(
        DateTimeImmutable $startDate,
        DateTimeImmutable $endDate,
        int $page,
        int $pagination
    ): string {
        return sprintf('plans_%s-%s:%d-%d', $startDate->format('Ymd'), $endDate->format('Ymd'), $page, $pagination);
    }

    private function getFromCache(string $cacheKey): ?Collection
    {
        $cachePlans = Cache::get($cacheKey);
        if ($cachePlans) {
            return collect($cachePlans);
        }

        return null;
    }

    private function storeInCache(string $cacheKey, Collection $plans, int $minutes = 10): void
    {
        if ($plans->isEmpty()) {
            return;
        }

        try {
            Cache::put($cacheKey, $plans->all(), now()->addMinutes($minutes));
        } catch (Exception $e) {
            Log::warning('Error saving plans cache: ' . $e->getMessage(), [
                'key' => $cacheKey,
                'total_items' => $plans->count(),
            ]);
        }
    }
}
