<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.is_stepper' => \App\Http\Middleware\CheckStepper::class,
            'checkUserType' => \App\Http\Middleware\CheckUserType::class,
            'checkIsApplied' => \App\Http\Middleware\CheckIsApplied::class,
            'blocked' => \App\Http\Middleware\BlockedUser::class,
        ]);
    })->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('class:close-expired')->daily();
        $schedule->command('block:reported-users')->daily();
        $schedule->command('unblock:reported-users')->hourly();
        $schedule->command('update:recurring-schedule-dates')->daily();
    })
    ->create();

