<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Rating\Jobs\ProductRating;

class Kernel extends ConsoleKernel
{
    // php artisan schedule:work
    protected function schedule(Schedule $schedule)
    {
        // Schedule the ProductRating command to run every three minutes
        $schedule->command('rating:calculate')->everyThreeMinutes();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        $this->load(base_path('Modules/Rating/Console'));

        require base_path('routes/console.php');
    }
}
