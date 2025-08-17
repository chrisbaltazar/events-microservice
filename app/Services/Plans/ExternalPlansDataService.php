<?php

namespace App\Services\Plans;

use App\Dto\Plans\BasePlanDto;
use App\Dto\Plans\PlanDto;
use App\Dto\Plans\ZoneDto;
use App\Exceptions\Plans\ExternalPlansHttpException;
use App\Repositories\BasePlanRepository;
use App\Repositories\PlanRepository;
use App\Repositories\ZoneRepository;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

readonly class ExternalPlansDataService
{

    public const SELL_MODE_KEY = 'sellMode';
    public const SELL_MODE_ONLINE = 'online';

    public function __construct(
        private ExternalPlansClientInterface $client,
        private ExternalPlansParserInterface $contentParser,
        private BasePlanRepository $basePlanRepository,
        private PlanRepository $planRepository,
        private ZoneRepository $zoneRepository,
    ) {
    }

    public function fetchAndUpdate(): void
    {
        Log::debug('Fetch and update plans from external provider. Started');

        $apiPlans = $this->getApiPlans();

        if (!$apiPlans) {
            return;
        }

        $this->persist($apiPlans);

        Log::debug('Fetch and update plans from external provider. Finished');
    }

    private function getApiPlans(): ?Collection
    {
        try {
            $response = $this->client->fetch();

            $allPlans = $this->contentParser->parse($response);

            return collect($allPlans)->where(self::SELL_MODE_KEY, self::SELL_MODE_ONLINE);
        } catch (ExternalPlansHttpException $e) {
            Log::warning($e->getMessage());
        } catch (Exception $e) {
            Log::error('[PlansProviderService]: ' . $e->getMessage());
        }

        return null;
    }

    private function persist(Collection $basePlanCollection): void
    {
        $basePlanCollection->each(function (BasePlanDto $baseDto) {
            $dbBasePlan = $this->basePlanRepository->save(
                array_merge(
                    $baseDto->asArray(),
                    [
                        'external_id' => $baseDto->id,
                        'id' => null, // remove to match the db with external id
                    ]
                )
            );

            $baseDto->plans->each(function (PlanDto $planDto) use ($baseDto, $dbBasePlan) {
                $dbPlan = $this->planRepository->save(
                    array_merge(
                        $planDto->asArray(),
                        [
                            'base_plan_id' => $dbBasePlan->id,
                            'external_id' => $planDto->id,
                            'external_base_id' => $baseDto->id,
                            'start_date' => $planDto->startDate->format('Y-m-d H:i:s'),
                            'end_date' => $planDto->endDate->format('Y-m-d H:i:s'),
                            'id' => null, // remove to match the db with external id
                        ]
                    )
                );

                $planDto->zones->each(function (ZoneDto $zoneDto) use ($planDto, $dbPlan) {
                    $this->zoneRepository->save(
                        array_merge(
                            $zoneDto->asArray(),
                            [
                                'plan_id' => $dbPlan->id,
                                'external_id' => $zoneDto->id,
                                'external_plan_id' => $planDto->id,
                                'id' => null, // remove to match the db with external id
                            ]
                        )
                    );
                });
            });
        });
    }
}
