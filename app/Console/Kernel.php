<?php

namespace App\Console;

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
        \App\Console\Commands\RepeatTasks::class,
        \App\Console\Commands\PushFcmNotification::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('kirim:artikel')->dailyAt('08:30');
        $schedule->command('tasks:repeat')->daily();
        $schedule->command('slack:attendance-report')->dailyAt('16:00');
        $schedule->command('fcm:push --data=screen=absen --shift=PG')->dailyAt('09:00');
        $schedule->command('fcm:push --data=screen=absen --shift=MD')->dailyAt('15:00');
        $schedule->command('fcm:push --data=screen=absen --shift=ML')->dailyAt('20:00');
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
