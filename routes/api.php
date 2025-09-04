<?php

//use Illuminate\Http\Request;
use App\Http\Controllers\Frontend\User\StaffAttendanceController;
use App\Http\Controllers\Frontend\User\VehiclesController;
use App\Http\Controllers\Frontend\User\LogkeepsController;
use App\Http\Controllers\Frontend\User\StaffManagementController;
use App\Http\Controllers\Frontend\User\MsMigrationResolutionController;
use App\Http\Controllers\EmailSendingApiController;
use App\Http\Controllers\Frontend\User\FlightEnvelopeController;
use App\Http\Controllers\Frontend\User\TicketGlitchesController;
use App\Http\Controllers\Frontend\User\DisruptionLogsController;
use App\Http\Controllers\Frontend\User\FinanceRaController;
use App\Http\Controllers\Frontend\User\FuelConsumptionController;
use App\Http\Controllers\Frontend\User\FlightOpsSummariesController;
use App\Http\Controllers\StaffTravelPublicController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('check-staff-info', [StaffAttendanceController::class, 'checkStaffInfo'])->name('check_staff_info');
Route::post('mark-attendance', [StaffAttendanceController::class, 'checkARANumber'])->name('mark_attendance');
Route::get('getIndividualStaffAttendance', [StaffAttendanceController::class, 'getIndividualStaffAttendance'])->name('getIndividualStaffAttendance');
Route::post('search-vehicle', [VehiclesController::class, 'search'])->name('search.vehicle');
Route::get('getAttendanceDailySummariesApi', [\App\Http\Controllers\AttendanceSummaryController::class, 'getAttendanceDailySummariesApi']);


Route::post('log-keeping', [LogkeepsController::class, 'store'])->name('store.logkeep');
Route::post('store-email', [StaffManagementController::class, 'storeEmail'])->name('storeEmail');
Route::post('store-ms-email', [MsMigrationResolutionController::class, 'storeEmail'])->name('storeMsEmail');
Route::get('get-log-keeping-stream/{meeting}', [LogkeepsController::class, 'getNewLogs'])->name('get.logstream');
Route::post('email-api', [EmailSendingApiController::class, 'storeEmailRequest']);
Route::post('save-field-data', [FlightEnvelopeController::class, 'saveFieldData'])->name('saveFieldData');
Route::post('glitches/update-pnr', [TicketGlitchesController::class, 'updatePNR'])->name('glitches.updatePNR');
Route::post('flight_disruption', [DisruptionLogsController::class, 'update'])->name('flight_disruption.update');
Route::delete('flight_disruption/{id}', [DisruptionLogsController::class, 'destroy'])->name('flight_disruption.delete');
Route::post('finance_ra', [FinanceRaController::class, 'update'])->name('finance_ra.update');
Route::delete('finance_ra/{id}', [FinanceRaController::class, 'destroy'])->name('finance_ra.delete');

Route::post('fuel_consumption_report', [FuelConsumptionController::class, 'update'])->name('fuel_consumption_report.update');
Route::delete('fuel_consumption_report/{id}', [FuelConsumptionController::class, 'destroy'])->name('fuel_consumption_report.delete');

Route::post('flight_ops_summaries', [FlightOpsSummariesController::class, 'update'])->name('flight_ops_summaries.update');
Route::delete('flight_ops_summaries/{id}', [FlightOpsSummariesController::class, 'destroy'])->name('fuel_consumption_report.delete');

Route::post('validate-login', [StaffTravelPublicController::class, 'validateLogin'])->name('validate_login');
Route::post('finalize-booking', [StaffTravelPublicController::class, 'finalizeBooking'])->name('finalize_booking');

Route::post('verify-tfm-otp', [\App\Http\Controllers\LegalPublicController::class, 'verifyOTPforTFM']);
