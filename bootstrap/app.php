<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\Commands\MarkAlphaAbsence;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo('/login');
        $middleware->alias([
            'prevent.back' => \App\Http\Middleware\PreventBackHistory::class,
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command(MarkAlphaAbsence::class)->dailyAt('13:32');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
