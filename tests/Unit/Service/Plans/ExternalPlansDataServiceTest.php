<?php

namespace Tests\Unit\Service\Plans;

use App\Models\BasePlan;
use App\Models\Plan;
use App\Repositories\BasePlanRepository;
use App\Repositories\PlanRepository;
use App\Repositories\ZoneRepository;
use App\Services\Plans\ExternalPlansClientInterface;
use App\Services\Plans\ExternalPlansDataService;
use App\Services\Plans\ExternalPlansParserInterface;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Dto\BasePlanDtoMother;
use Tests\Unit\Dto\PlanDtoMother;
use Tests\Unit\Dto\ZoneDtoMother;

class ExternalPlansDataServiceTest extends TestCase
{

    public function test_process_ok(): void
    {
        $test = $this->getTest();

        Log::shouldReceive('debug')->twice();

        $test->fetchAndUpdate();
    }

    private function getClientMock()
    {
        $client = $this->createMock(ExternalPlansClientInterface::class);
        $client->expects($this->once())->method('fetch')->willReturn(
            '<planList xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1.0" xsi:noNamespaceSchemaLocation="planList.xsd">
<output>
<base_plan base_plan_id="291" sell_mode="online" title="Camela en concierto">
<plan plan_start_date="2021-06-30T21:00:00" plan_end_date="2021-06-30T22:00:00" plan_id="291" sell_from="2020-07-01T00:00:00" sell_to="2021-06-30T20:00:00" sold_out="false">
<zone zone_id="40" capacity="240" price="20.00" name="Platea" numbered="true"/>
<zone zone_id="38" capacity="50" price="15.00" name="Grada 2" numbered="false"/>
<zone zone_id="30" capacity="90" price="30.00" name="A28" numbered="true"/>
</plan>
<base_plan base_plan_id="444" sell_mode="offline" organizer_company_id="1" title="Tributo a Juanito Valderrama">
<plan plan_start_date="2021-09-31T20:00:00" plan_end_date="2021-09-31T21:00:00" plan_id="1642" sell_from="2021-02-10T00:00:00" sell_to="2021-09-31T19:50:00" sold_out="false">
<zone zone_id="7" capacity="22" price="65.00" name="Amfiteatre" numbered="false"/>
</plan>
</base_plan>
</output>
</planList>'
        );

        return $client;
    }

    private function getParserMock()
    {
        $parser = $this->createMock(ExternalPlansParserInterface::class);
        $parser->expects($this->once())->method('parse')->willReturn(
            [
                BasePlanDtoMother::random([
                    'sellMode' => 'online',
                    'plans' => collect([
                        PlanDtoMother::random([
                            'zones' => collect([ZoneDtoMother::random(), ZoneDtoMother::random()]),
                        ]),
                    ]),
                ]),
                BasePlanDtoMother::random([
                    'sellMode' => 'offline',
                ]),
            ]
        );

        return $parser;
    }

    private function getTest()
    {
        $basePlanRepo = $this->createMock(BasePlanRepository::class);
        $basePlanRepo->expects($this->once())->method('save')->willReturn(
            new BasePlan(['id' => 1])
        );
        $planRepo = $this->createMock(PlanRepository::class);
        $planRepo->expects($this->once())->method('save')->willReturn(new Plan(['id' => 2]));
        $zoneRepo = $this->createMock(ZoneRepository::class);
        $zoneRepo->expects($this->exactly(2))->method('save');

        return new ExternalPlansDataService(
            $this->getClientMock(),
            $this->getParserMock(),
            $basePlanRepo,
            $planRepo,
            $zoneRepo
        );
    }
}
