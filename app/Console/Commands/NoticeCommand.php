<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\ScheduleController;

class NoticeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:publicizeAt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checkear para publicar una noticia a cada minuto';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $scheduleInstance = new ScheduleController();
        $scheduleInstance->publicizeAtNotice();
        $this->info('Tarea ejecutada...');
        return 0;
    }
}
