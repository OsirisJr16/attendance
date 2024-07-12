<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ContractTypeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\PosteController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('employees', EmployeeController::class);
Route::resource('contract-types', ContractTypeController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('leaves', LeaveController::class);
Route::resource('postes',PosteController::class);
Route::get('/download-report/{date}', function ($date) {
    $filePath = storage_path('app/public/reports/daily_report_' . $date . '.pdf');

    if (!Storage::exists($filePath)) {
        abort(404, 'File not found');
    }

    return response()->download($filePath);
})->name('download.report');
