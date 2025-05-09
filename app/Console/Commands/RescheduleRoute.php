<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Route_lists;

class RescheduleRoute extends Command
{
    protected $signature = 'reschedule:route';
    protected $description = 'Reschedules old routes to start from today if pending';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $today = date('Y-m-d');
        $old_route_lists = Route_lists::where('status', 'pending')
            ->where('start_date', '<', $today)
            ->whereHas('route', function ($query) {
                $query->whereNull('deleted');
            })->get();

        if($old_route_lists){
            foreach($old_route_lists as $list){
                $list->start_date = $today;
                $list->tech_id = null;
                $list->save();
            }
        }

        $this->info('Routes have been rescheduled.');
    }
}