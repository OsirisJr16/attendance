<?php

namespace App\Console\Commands;

use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateLeaveStatus extends Command
{

    protected $signature = 'leave:update-status';
    protected $description = 'Update leave status for employees';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $leaves = Leave::all();

        foreach ($leaves as $leave) {
            $currentDate = Carbon::now()->toDateString();
            if ($leave->start_date <= $currentDate && $leave->end_date >= $currentDate) {
                $leave->on_leave = true;
            } else {
                $leave->on_leave = false;
            }
            $leave->save();
        }

        $this->info('Leave status updated successfully!');
    }
}
