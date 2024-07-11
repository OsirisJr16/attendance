<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\ContractType;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Dompdf\Dompdf;
use Dompdf\Options;

class GenerateDailyReport extends Command
{
    protected $signature = 'generate:daily-report';

    protected $description = 'Generate daily attendance report in PDF';

    public function handle()
    {
        $date = Carbon::yesterday();
        $employees = Employee::all();

        $data = [];

        foreach ($employees as $employee) {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereDate('date', $date)
                ->first();

            if ($attendance) {
                $totalHours = $attendance->check_in && $attendance->check_out
                    ? Carbon::parse($attendance->check_in)->diffInHours(Carbon::parse($attendance->check_out))
                    : 'Absence';

                if ($attendance->status === 'present' && !$attendance->check_out) {
                    $totalHours = 'Présence pas finie';
                }
            } else {
                $totalHours = 'Absence';
            }

            $leave = Leave::where('employee_id', $employee->id)
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->first();

            if ($leave && $leave->on_leave) {
                $totalHours = 'Congé';
            }

            $contractType = ContractType::find($employee->contract_type_id);

            $data[] = [
                'first_name' => $employee->first_name,
                'last_name' => $employee->last_name,
                'contract_type' => $contractType ? $contractType->name : '',
                'total_hours' => $totalHours,
            ];
        }

        $html = view('reports.daily', compact('data'))->render();

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();

        $pdfFilepath = storage_path('app/public/reports/daily_report_' . $date->format('Y-m-d') . '.pdf');
        file_put_contents($pdfFilepath, $dompdf->output());

        $this->info('Daily report generated successfully for ' . $date->toDateString());
    }
}
