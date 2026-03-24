<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        //\App\Console\Commands\CleanProductsKeepFirst1000::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Optional: run daily
        // $schedule->command('clean:products-keep-1000')->dailyAt('03:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}