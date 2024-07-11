<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leave = Leave::with('employee:id,first_name,last_name,email')->get();
        return response()->json($leave);
    }

    public function store(Request $request)
    {
        $leave = new Leave();
        $leave->employee_id = $request->employee_id;
        $leave->start_date = $request->start_date;
        $leave->end_date = $request->end_date;
        $leave->reason = $request->reason;
        $leave->on_leave = $this->isOnLeave($leave->start_date, $leave->end_date);

        $leave->save();

        return response()->json($leave, 201);
    }

    public function show($id)
    {
        return Leave::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update($request->all());
        return response()->json($leave, 200);
    }

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();
        return response()->json(null, 204);
    }

    private function isOnLeave($start_date, $end_date)
    {
        $currentDate = Carbon::now()->toDateString();
        return $start_date <= $currentDate && $end_date >= $currentDate;
    }
}
