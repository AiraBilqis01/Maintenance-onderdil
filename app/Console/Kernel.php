<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /* Define the application's command schedule. */
    protected function schedule(Schedule $schedule): void
    {
        // Pemeriksaan pengingat H-1 dan hari H setiap jam.
        $schedule->command('jadwal:kirim-pengingat')
            ->hourly()
            ->timezone('Asia/Jakarta')
            ->between('07:00', '23:59')
            ->withoutOverlapping(60)
            ->sendOutputTo(
                storage_path('logs/pengingat-jadwal.log')
            );
    }

    /* Register the commands for the application. */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
