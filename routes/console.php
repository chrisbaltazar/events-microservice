<?php

use App\Services\Plans\ExternalPlansDataService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('app:plans:update', function () {
    App::make(ExternalPlansDataService::class)->fetchAndUpdate();
})->purpose('Fetch and update plans from external provider');

Schedule::command('app:plans:update')->everyMinute();
