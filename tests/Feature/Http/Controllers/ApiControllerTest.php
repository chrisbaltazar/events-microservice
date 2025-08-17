<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\BasePlan;
use App\Models\Plan;
use App\Models\Zone;
use App\Services\UseCases\PlansDateSearchAction;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_error(): void
    {
        $response = $this->get('/api/search');
        $response->assertBadRequest();

        $response = $this->get('api/search?start_date=foo&end_date=bar');
        $response->assertBadRequest();

        $response = $this->get('api/search?start_date=2025-01-01&end_date=2024-01-01');
        $response->assertBadRequest();
    }

    public function test_content_not_found()
    {
        $planService = $this->createMock(PlansDateSearchAction::class);
        $planService->method('__invoke')->willReturn([]);
        $this->app->instance(PlansDateSearchAction::class, $planService);

        $response = $this->get('api/search?start_date=2025-01-01&end_date=2025-01-31');

        $response->assertNotFound();
    }

    public function test_content_filtered()
    {
        $basePlan = BasePlan::factory()->create();

        Plan::factory()->has(
            Zone::factory()->state(
                new Sequence(
                    fn($sequence) => ['price' => 100],
                    fn($sequence) => ['price' => 200],
                    fn($sequence) => ['price' => 300],
                )
            )->count(3)
        )->create([
            'base_plan_id' => $basePlan->id,
            'start_date' => '2025-01-01 00:00:00',
            'end_date' => '2025-01-01 01:00:00',
        ]);

        Plan::factory()->has(
            Zone::factory()->state(
                new Sequence(
                    fn($sequence) => ['price' => 200],
                    fn($sequence) => ['price' => 100],
                    fn($sequence) => ['price' => 500],
                )
            )->count(3)
        )->create([
            'base_plan_id' => $basePlan->id,
            'start_date' => '2025-02-01 10:00:00',
            'end_date' => '2025-02-01 11:00:00',
        ]);

        $response = $this->get('api/search?start_date=2025-01-01&end_date=2025-01-31');
        $response->assertOk();
        $response->assertJsonCount(1, 'data.events');
        $response->assertJsonFragment(['min_price' => 100, 'max_price' => 300]);

        $response = $this->get('api/search?start_date=2025-02-01&end_date=2025-02-02');
        $response->assertOk();
        $response->assertJsonCount(1, 'data.events');
        $response->assertJsonFragment(['min_price' => 100, 'max_price' => 500]);
    }
}
