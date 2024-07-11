<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendance = Attendance::with('employee:id,first_name,last_name,email')->get();
        return response()->json($attendance);
    }

    public function store(Request $request)
    {
        $attendance = new Attendance();
        $attendance->employee_id = $request->employee_id;
        $attendance->date = $request->date;
        $attendance->check_in = $request->check_in;
        $attendance->check_out = $request->check_out;

        $leave = Leave::where('employee_id', $request->employee_id)
            ->where('start_date', '<=', $request->date)
            ->where('end_date', '>=', $request->date)
            ->first();

        if ($leave && $leave->on_leave) {
            $attendance->status = 'on_leave';
        } elseif ($request->check_in && $request->check_out) {
            $attendance->status = 'present';
        } else {
            $attendance->status = 'absent';
        }

        $attendance->save();

        return response()->json($attendance, 201);
    }

    public function show($id)
    {
        return Attendance::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->update($request->all());
        return response()->json($attendance, 200);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json(null, 204);
    }
}
