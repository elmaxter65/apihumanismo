<?php

namespace App\Console;

use App\Http\Controllers\ScheduleController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\GetCryptosTasks',
        'App\Console\Commands\NoticeCommand'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('getcryptos:tasks')->everyTenMinutes();
        //$schedule->command('getcryptos:tasks')->everyMinute();

        $schedule->call( function () {
            $scheduleInstance = new ScheduleController();
            $scheduleInstance->publicizeAtNotice();
        } )->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
