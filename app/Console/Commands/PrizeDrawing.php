<?php

namespace App\Console\Commands;

use App\Models\PrizeDrawingCalendar;
use App\Services\PrizeService;
use Illuminate\Console\Command;

class PrizeDrawing extends Command
{

    public function __construct(public PrizeService $service)
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prize-drawing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prize drawing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $calendar = PrizeDrawingCalendar::where('drawing_at', '<=', now())->whereNull('started_at')->where('is_finished', 0)->first();
        $calendar->started_at = now();
        if($this->service->draw($calendar->prize_id)) {
            $calendar->is_finished = 1;
        }
        $calendar->save();
        $this->info('Finished');
    }
}
