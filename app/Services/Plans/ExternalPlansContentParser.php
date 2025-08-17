<?php

namespace App\Services\Plans;

use App\Dto\Plans\BasePlanDto;
use App\Dto\Plans\PlanDto;
use App\Dto\Plans\ZoneDto;
use DateTimeImmutable;
use http\Exception\UnexpectedValueException;
use Orchestra\Parser\Xml\Facade as XMLParser;
use SimpleXMLElement;

class ExternalPlansContentParser implements ExternalPlansParserInterface
{
    public function parse(string $content): array
    {
        $allPlans = [];
        /** @var SimpleXMLElement $xml */
        $xml = XMLParser::extract($content)->getContent();
        $basePlans = $xml->output->children() ?? throw new UnexpectedValueException(
            'Wrong XML format. Missing output/base_plan'
        );

        /** @var SimpleXMLElement $basePlan */
        foreach ($basePlans as $basePlan) {
            $plans = [];
            $basePlanId = current($basePlan['base_plan_id']);
            $basePlanTitle = current($basePlan['title']);
            $basePlanMode = current($basePlan['sell_mode']);
            /** @var SimpleXMLElement $plan */
            foreach ($basePlan->children() as $plan) {
                $zones = [];
                $planId = current($plan['plan_id']);
                $startDate = current($plan['plan_start_date']);
                $endDate = current($plan['plan_end_date']);
                /** @var SimpleXMLElement $zone */
                foreach ($plan->children() as $zone) {
                    $zones[] = new ZoneDto(
                        current($zone['zone_id']),
                        $planId,
                        current($zone['name']),
                        current($zone['price']),
                    );
                }
                $plans[] = new PlanDto(
                    $planId,
                    $basePlanId,
                    new DateTimeImmutable($startDate),
                    new DateTimeImmutable($endDate),
                    collect($zones)
                );
            }
            $allPlans[] = new BasePlanDto(
                $basePlanId,
                $basePlanTitle,
                $basePlanMode,
                collect($plans)
            );
        }

        return $allPlans;
    }
}
