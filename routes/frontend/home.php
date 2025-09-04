<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\CallCenterController;
use App\Http\Controllers\Frontend\User\ArikHmoController;
use App\Http\Controllers\Frontend\User\StaffMemberOccasionController;
use App\Http\Controllers\Frontend\User\StaffTravelFormerController;
use App\Http\Controllers\Frontend\User\StaffTravelController;
use App\Http\Controllers\Frontend\User\StaffAttendanceController;
use App\Http\Controllers\Frontend\User\VehiclesController;
use App\Http\Controllers\Frontend\User\StaffTravelAccessController;
use App\Http\Controllers\Frontend\User\FCNIController;
use App\Http\Controllers\Frontend\User\TourOperationsController;
//use App\Http\Controllers\Frontend\User\TourOperationsManagementController;
use App\Http\Controllers\Frontend\User\StaffAttendanceMiscController;
use App\Http\Controllers\Frontend\User\StaffAttendanceAuthorizationsController;
use App\Http\Controllers\Frontend\User\StaffAttendanceAltController;
use App\Http\Controllers\Frontend\User\AssetRegisterController;
use App\Http\Controllers\Frontend\User\LogkeepsController;
use App\Http\Controllers\Frontend\User\ErpsController;
use App\Http\Controllers\Frontend\User\StaffManagementController;
use App\Http\Controllers\Frontend\User\MsMigrationResolutionController;
use App\Http\Controllers\Frontend\User\VacancyController;
use App\Http\Controllers\Frontend\User\VacanciesBackendController;
use App\Http\Controllers\Frontend\User\JobApplicationController;
use App\Http\Controllers\Frontend\User\ServiceNow\TicketsController;
use App\Http\Controllers\Frontend\User\FormEngine\FormsManagementController;
use App\Http\Controllers\StaffAttendanceArchiverController;
use App\Http\Controllers\Frontend\User\FuelDiscrepanciesController;
use App\Http\Controllers\Frontend\User\FlightEnvelopeController;
use App\Http\Controllers\Frontend\User\TicketGlitchesController;
use App\Http\Controllers\Frontend\User\PaxComplaintsController;
use App\Http\Controllers\Frontend\User\BusinessGoalsController;
use App\Http\Controllers\Frontend\User\DisruptionLogsController;
use App\Http\Controllers\Frontend\User\FinanceRaController;
use App\Http\Controllers\Frontend\User\FuelConsumptionController;
use App\Http\Controllers\Frontend\User\FlightOpsSummariesController;
use App\Http\Controllers\AttendanceSummaryController;
use App\Http\Controllers\Frontend\User\AircraftStatusController;
use App\Http\Controllers\Frontend\User\AcfaController;
use App\Http\Controllers\Frontend\User\SRBController;
use App\Http\Controllers\Frontend\User\SpiPermissionController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\Frontend\User\CugLineController;
use App\Http\Controllers\Frontend\User\FtpController;
use App\Http\Controllers\Frontend\User\LAndDTrainingCourseController;
use App\Http\Controllers\Frontend\User\EcsClientController;
use App\Http\Controllers\Frontend\User\EcsBookingController;
use App\Http\Controllers\Frontend\User\EcsReconciliationController;
use App\Http\Controllers\Frontend\User\EcsRefundController;
use App\Http\Controllers\Frontend\User\EcsExternalClientController;
use App\Http\Controllers\Frontend\User\EcsClientAccountSummaryController;
use App\Http\Controllers\Frontend\User\StaffTravelBeneficiaryController;
use App\Http\Controllers\Frontend\User\StbLoginLogController;
use App\Http\Controllers\Frontend\User\ServiceNowGroupAgentController;
use App\Http\Controllers\Frontend\User\ServiceNowGroupViewerController;
//use App\Http\Controllers\Frontend\User\EcsFlightTransactionController;
use App\Http\Controllers\Frontend\User\EcsFlightController;
use App\Http\Controllers\Frontend\User\EcsFlightAjaxController;
use App\Http\Controllers\Frontend\User\StbHrController;
use App\Http\Controllers\Frontend\User\LegalTeamExternalLawyerController;
use App\Http\Controllers\Frontend\User\LegalTeamFolderAjaxController;
use App\Http\Controllers\Frontend\User\LegalTeamFolderAccessAjaxController;
use App\Http\Controllers\Frontend\User\LegalTeamDocumentController;
use App\Http\Controllers\Frontend\User\EcsFlightTransactionAjaxController;
use App\Http\Controllers\Frontend\User\IcuActivityAjaxController;
use App\Http\Controllers\Frontend\User\ExchangeRateAjaxController;
use App\Http\Controllers\Frontend\User\ExternalVendorAjaxController;
use App\Http\Controllers\Frontend\User\QaLetterAjaxController;
use App\Http\Controllers\Frontend\User\EcsPortalUserAjaxController;
use App\Http\Controllers\Frontend\User\EcsInternalDashController;




//use App\Http\Controllers\RoughNotesController;
//use App\Http\Controllers\RNOneController;


/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::view('fitness-demo', 'frontend.user.fitness-demo');

Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('outbound-messages/email', [\App\Http\Controllers\OutboundMessagesController::class, 'emailWorker'])->name('emailWorker');
Route::get('outbound-messages/whatsapp', [\App\Http\Controllers\OutboundMessagesController::class, 'whatsappWorker']);
Route::get('bsc-reminders', [\App\Http\Controllers\WorkerController::class, 'checkUnfilledBusinessScoreCards']);
Route::get('hods-reminders', [\App\Http\Controllers\WorkerController::class, 'HODsReminder']);
//Route::get('hods-email-draft', [\App\Http\Controllers\WorkerController::class, 'HODsLinkText']);

Route::get('acfa-cron-job', [\App\Http\Controllers\AcfaPublicController::class, 'cronJobSingleAirline']);

Route::get('ticketWatcher', [\App\Http\Controllers\Frontend\User\ServiceNow\TicketsController::class, 'taskWatcher']);

Route::get('archive-staff-attendance', [StaffAttendanceArchiverController::class, 'archiveStaffAttendance'])->name('archive.staff.attendance');
Route::get('previous-day-attendance-archiver', [StaffAttendanceArchiverController::class, 'previousDayArchiver'])->name('previous.day.attendance.archiver');

Route::get('processHistoricalDaysFromDB', [AttendanceSummaryController::class, 'processHistoricalDaysFromDB'])->name('processHistoricalDaysFromDB');
Route::get('processHistoricalDailySummaries', [AttendanceSummaryController::class, 'processHistoricalDailySummaries'])->name('processHistoricalDailySummaries');
Route::get('processDailySummary', [AttendanceSummaryController::class, 'processDailySummary'])->name('processDailySummary');
Route::get('process-weekly-summary', [AttendanceSummaryController::class, 'processWeeklySummary']);
Route::get('process-historical-weekly-summary', [AttendanceSummaryController::class, 'processHistoricalWeeklySummary']);

Route::get('deactivate-staff', [StaffManagementController::class, 'deactivateStaff'])->name('deactivateStaff'); // To be run by a worker

Route::get('verify-auth', [StaffTravelAccessController::class, 'verifyAuth']);
Route::get('pax-complaints-qr-image', [PaxComplaintsController::class, 'showQR'])->name('qr_code');
Route::get('pax-complaints-create', [PaxComplaintsController::class, 'create'])->name('pax.complaints.create');

Route::get('multi-business-areas-pdf', [BusinessGoalsController::class, 'multiBusinessAreaTables']);
// Rough notes start
//Route::get('checkDaySchedule', [StaffAttendanceController::class, 'checkDayScheduletest']);
//Route::get('addPaypoints', [RoughNotesController::class, 'addPaypoints']);
//Route::get('emailAdding', [RoughNotesController::class, 'emailAdding']);
//Route::get('commercialBSC', [RoughNotesController::class, 'commercialBSC']);
//Route::get('updateStaffData', [RoughNotesController::class, 'updateStaffData']);
//Route::get('addContractTempStaff', [RoughNotesController::class, 'addContractTempStaff']);
//Route::get('addStaffSchedules', [RoughNotesController::class, 'addStaffSchedules']);
//Route::get('getStaffMemberIDCardStats', [RoughNotesController::class, 'getStaffMemberIDCardStats']);
//Route::get('setPilotsPermissions', [RoughNotesController::class, 'setPilotsPermissions']);
//Route::get('updateStaffMembers', [RoughNotesController::class, 'updateStaffMembers']);
//Route::get('importFromArray', [FuelConsumptionController::class, 'importFromArray']);
//Route::get('updateContractTempStaff', [RNOneController::class, 'updateContractTempStaff']);
//Route::get('updatePermanentStaff', [RNOneController::class, 'updatePermanentStaff']);
//Route::get('setShiftStatus', [RNOneController::class, 'setShiftStatus']);
//Route::get('sortIDcards', [RNOneController::class, 'sortIDcards']);
//Route::get('addPilotUsers', [RNOneController::class, 'addPilotUsers']);
//Route::get('bulkDeactivationInitiation', [RNOneController::class, 'bulkDeactivationInitiation']);

//Route::get('updateDateFormat', [FinanceRaController::class, 'updateDateFormat']);
Route::get('updateTextToNumbers', [FinanceRaController::class, 'updateTextToNumbers']);
//Route::get('getPremisesRecs',
//[\App\Http\Controllers\Frontend\User\StaffAttendanceMiscController::class, 'getPremisesRecs']);
// Rough notes end

/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {

    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::get('outgoing-messages-log', [DashboardController::class, 'outgoingMessages'])->name('outgoingMessages');

    Route::group(['namespace' => 'User', 'as' => 'user.'], function () {
        // User Dashboard Specific
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('i-dashboard', function(){
            return view('iframe');
        })->name('i_dashboard');

        // User Account Specific
        Route::get('account', [AccountController::class, 'index'])->name('account');

        // User Profile Specific
        Route::group(['middleware' => ['permission:update other staff info|manage own unit info']], function(){
            Route::get('staff-profiles', [ProfileController::class, 'staffMembersProfiles'])->name('profiles');
            Route::post('init-deactivate-staff', [StaffManagementController::class, 'storeStaffDeactivation'])->name('init.deactivateStaff');
            Route::post('add-remote-schedule', [StaffManagementController::class, 'storeRemoteSchedule'])->name('storeRemoteSchedule');

        });
        Route::resource('staff', '\App\Http\Controllers\Frontend\User\StaffController')->middleware('permission:update other staff info');

        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('profile/id-card', [ProfileController::class, 'editIDcard'])->name('profile.editIDcard');
        Route::post('profile/id-card', [ProfileController::class, 'uploadIDCard'])->name('profile.uploadIDcard');
        Route::post('updateManager', [ProfileController::class, 'updateManager'])->name('profile.updateManager');
    });

    Route::group(['namespace' => 'User', 'as' => 'call_center.', 'prefix' => 'call-center-crm'], function () {
        Route::get('/', [CallCenterController::class, 'index'])->name('index');
        Route::get('create', [CallCenterController::class, 'create'])->name('create.log');
        Route::post('store', [CallCenterController::class, 'store'])->name('store.log');
        Route::get('logs', [CallCenterController::class, 'logs'])->name('logs');
        Route::get('log/{call_center_log}', [CallCenterController::class, 'show'])->name('view.log');
    });

    Route::group(['namespace' => 'User', 'as' => 'hmo.', 'prefix' => 'hmo'], function () {
        Route::get('/', [ArikHmoController::class, 'index'])->name('staff_member.index');
        Route::get('family_members', [ArikHmoController::class, 'family_members'])->name('family_members');
        Route::get('staff_member/{ara_number}', [ArikHmoController::class, 'show'])->name('show.staff_member');
        Route::post('staff_member/{ara_number}', [ArikHmoController::class, 'update'])->name('update.staff_member');
        Route::post('staff_member/{ara_number}/add-family-member', [ArikHmoController::class, 'addFamilyMember'])->name('staff_member.addFamilyMember');
        Route::get('staff_member/{ara_number}/remove-family-member', [ArikHmoController::class, 'removeFamilyMember'])->name('staff_member.removeFamilyMember');
        Route::get('staff_member/{ara_number}/family-member/{family_member}', [ArikHmoController::class, 'familyMember'])->name('staff_member.familyMember');
        Route::post('staff_member/{ara_number}/family-member/{family_member}', [ArikHmoController::class, 'updateFamilyMember'])->name('staff_member.familyMember.update');
    });

    Route::group(['namespace' => 'User', 'as' => 'occasions.', 'prefix' => 'occasions'], function () {
        Route::get('{slug}', [StaffMemberOccasionController::class, 'occasion'])->name('show');
        Route::post('{slug}/add-message', [StaffMemberOccasionController::class, 'addMessage'])->name('addMessage');
    });


    Route::group(['namespace' => 'User', 'as' => 'staff_travel.', 'prefix' => 'staff_travel'], function () {
        Route::get('/', [StaffTravelFormerController::class, 'index'])->name('index');
        Route::get('bookings', [StaffTravelFormerController::class, 'bookings'])->name('bookings');
        Route::get('staff-travel-portal', [StaffTravelFormerController::class, 'staffTravelPortal'])->name('staff_travel_portal');
        Route::get('my-bookings', [StaffTravelFormerController::class, 'myBookings'])->name('my_bookings');
        Route::get('make-booking', [StaffTravelController::class, 'makeBooking'])->name('make_booking');
        Route::post('makeBooking', [StaffTravelController::class, 'bookingInit'])->name('bookingInit');

        Route::get('reset-password', [StaffTravelAccessController::class, 'sendPasswordResetEmail'])->name('reset_password_email');
        Route::post('verify-reset-code', [StaffTravelAccessController::class, 'verifyResetCode'])->name('verify_reset_code');

        Route::group(['middleware' => 'permission:manage staff travel platform'], function(){
            Route::get('stb-hr-mgt', [StbHrController::class, 'index'])->name('stbHrMGT');
            Route::post('store-window', [StbHrController::class, 'storeWindow'])->name('store.window');
            Route::post('close-window/{stbRegistrationWindow}', [StbHrController::class, 'closeWindow'])->name('close.window');

            Route::post('/permissions', [StbHrController::class, 'store'])->name('permissions.store');
            Route::put('/permissions/{id}', [StbHrController::class, 'update'])->name('permissions.update');
            Route::delete('/permissions/{id}', [StbHrController::class, 'destroy'])->name('permissions.delete');

            Route::post('send-email', [StbHrController::class, 'sendEmail'])->name('send.email');
        });
    });

    Route::group(['namespace' => 'User', 'as' => 'attendance.', 'prefix' => 'attendance'], function(){
        Route::group(['middleware' => 'permission:mark attendance'], function(){
       Route::get('/', [StaffAttendanceController::class, 'index'])->name('index');
    });

        Route::group(['middleware' => 'permission:mark outstation attendance'], function(){
            Route::get('outstation', [StaffAttendanceController::class, 'outstation'])->name('outstation');
        });

       Route::get('scan-qr', [StaffAttendanceController::class, 'qrCodeToScan'])->name('qrCodeToScan');
//       Route::get('staff-attendance', [StaffAttendanceController::class, 'viewIndividualStaffAttendance'])->name('view.individual.staff');
        Route::group(['middleware' => ['permission:update other staff info|manage own unit info|manage all staff attendance']], function() {
//            Route::get('multiple-staff-attendance', [StaffAttendanceController::class, 'viewMultipleStaffAttendance'])->name('multiple.staff.attendance');
            Route::post('notifyLateComer', [\App\Http\Controllers\Frontend\User\StaffAttendanceMiscController::class, 'notifyLateComer'])->name('notifyLateComer');
            Route::get('send-email', [StaffAttendanceMiscController::class, 'notifyLateComer'])->name('send.attendance.email');

            // alt
            Route::get('multiple-staff-attendance', [StaffAttendanceAltController::class, 'fetchStaffAttendance'])->name('multiple.staff.attendance');
            Route::get('staff-attendance/weekly-summaries', [StaffAttendanceAltController::class, 'showWeeklySummaries'])->name('staff_attendance.weekly_summaries');

            Route::get('staff-attendance-summaries', [StaffAttendanceAltController::class, 'attendanceSummaries'])->name('staff.attendance.summaries');

        });

    Route::group(['as' => 'holidays.', 'middleware' => ['permission:update other staff info|manage all staff attendance']], function(){
        Route::get('/holidays', 'HolidayController@holidays')->name('all');
        Route::get('/holidays-data', 'HolidayController@index')->name('index');
        Route::get('/holidays/{id}', 'HolidayController@show')->name('show');
        Route::post('/holidays', 'HolidayController@store')->name('store');
        Route::put('/holidays/{id}', 'HolidayController@update')->name('update');
        Route::delete('/holidays/{id}', 'HolidayController@destroy')->name('destroy');
    });

        Route::get('my-attendance', [StaffAttendanceAltController::class, 'fetchStaffAttendance'])->name('my.attendance');
        Route::get('managed-authorizations', [StaffAttendanceAuthorizationsController::class, 'userCreatedExemptions'])->name('managed.authorizations');
        Route::get('managed-staff', [StaffAttendanceAuthorizationsController::class, 'staffUnderMe'])->name('managed.staff');
        Route::get('create-manager-authorization', [StaffAttendanceAuthorizationsController::class, 'createExemption'])->name('create.manager.authorization');
        Route::post('create-manager-authorization', [StaffAttendanceAuthorizationsController::class, 'storeExemption'])->name('store.manager.authorization');

    });

    Route::group(['namespace' => 'User', 'as' => 'vehicle.', 'prefix' => 'vehicle'], function(){
       Route::post('store-vehicle', [VehiclesController::class, 'storeVehicle'])->name('store');
    });

    Route::group(['namespace' => 'User', 'as' => 'pilotLibrary.', 'prefix' => 'pilot-library'], function(){
        Route::group([
            'middleware' => ['permission:manage pilot elibrary']
        ], function(){
            Route::get('add-pdf', [FCNIController::class, 'create'])->name('create');
            Route::post('add-pdf', [FCNIController::class, 'store'])->name('store');
        });

        Route::group([
            'middleware' => ['permission:view Q400 PDFs|view 737 PDFs']
        ], function(){
            Route::get('/', [FCNIController::class, 'index'])->name('index');
            Route::post('mark-as-read', [FCNIController::class, 'markAsRead'])->name('mark.as.read');
            Route::get('/{pdf_file}', [FCNIController::class, 'show'])->name('show');
            Route::delete('/{pdf_file}', [FCNIController::class, 'destroy'])->name('delete');
        });

    });

    Route::group(['namespace' => 'User', 'as' => 'tour_operations.', 'prefix' => 'tour-operations', 'middleware' => ['permission:make tour bookings']], function(){
        Route::get('list', [TourOperationsController::class, 'pendings'])->name('passengers.list');
        Route::get('completed', [TourOperationsController::class, 'completed'])->name('passengers.completed.bookings');
        Route::get('my-opened-list', [TourOperationsController::class, 'myCurrentlyOpened'])->name('passengers.my.opened.list');
        Route::get('pax/check-locked-status', [TourOperationsController::class, 'checkLockStatus'])->name('passengers.checkLockStatus');
        Route::get('pax/{passenger}', [TourOperationsController::class, 'show'])->name('passengers.show');
        Route::post('pax/{passenger}', [TourOperationsController::class, 'update'])->name('passengers.update');
        Route::post('pax/{passenger}/unlock-for-viewing', [TourOperationsController::class, 'unlock'])->name('passengers.unlock');

        // for managers
        Route::resource('tros', 'TourOperationsManagementController');
    });

    // IT Assets
    Route::group(['namespace' => 'User', 'as' => 'it_assets.', 'prefix' => 'it-assets', 'middleware' => ['permission:manage IT assets']], function() {
        Route::get('staff-it-assets', [AssetRegisterController::class, 'staffItAssets'])->name('staff.it.assets');
        Route::get('dashboard', [AssetRegisterController::class, 'dashboard'])->name('dashboard');
        Route::get('assets-by-staff', [AssetRegisterController::class, 'assetsByStaff'])->name('assetsByStaff');
        Route::get('list', [AssetRegisterController::class, 'index'])->name('list');
        Route::get('create', [AssetRegisterController::class, 'create'])->name('create');
        Route::post('store', [AssetRegisterController::class, 'store'])->name('store');
        Route::get('edit/{it_asset}', [AssetRegisterController::class, 'edit'])->name('edit');
        Route::get('show/{it_asset}', [AssetRegisterController::class, 'show'])->name('show');
        Route::post('update/{it_asset}', [AssetRegisterController::class, 'update'])->name('update');
        Route::post('destroy/{it_asset}', [AssetRegisterController::class, 'destroy'])->name('destroy');
    });
Route::view('network-outage-analysis', 'frontend.network-outage-analyzer')->name('network.outage.analysis.tool');
    Route::group(['namespace' => 'User', 'as' => 'work_flows.', 'prefix' => 'workflows'], function (){
        Route::resource('workflow', 'DemoMemoController');
    });

    Route::group(['namespace' => 'User', 'as' => 'log_keeping.', 'prefix' => 'log_keeping', 'middleware' => ['permission:view logstreams']], function (){
        Route::group(['middleware' => ['permission:enter logkeeps']], function(){
            Route::get('erp/{erp}', [ErpsController::class, 'show'])->name('show.erp');
            Route::post('erps', [ErpsController::class, 'store'])->name('store.erp');
            Route::delete('erps/{erp}', [ErpsController::class, 'destroy'])->name('delete.erp');
            Route::patch('erps/{erp}', [ErpsController::class, 'update'])->name('update.erp');
            Route::post('log-keep-delete', [LogkeepsController::class, 'destroy'])->name('delete.logkeep');
        });
        Route::get('erps', [ErpsController::class, 'erps'])->name('erps');
        // Route::get('log-keeping', [LogkeepsController::class, 'index'])->name('index');
        Route::get('log-stream/{erp}', [LogkeepsController::class, 'logstream'])->name('logstream');

    });


    Route::group(['namespace' => 'User', 'as' => 'staff_info_management.', 'prefix' => 'staff_info_management', 'middleware' => ['permission:update other staff info|edit staff email']], function (){
        Route::get('emailFix', [StaffManagementController::class, 'emailFix'])->name('emailFix');
        Route::get('ms-email', [MsMigrationResolutionController::class, 'index'])->name('ms.email');
        Route::get('add-staff', [StaffManagementController::class, 'createStaffForm'])->name('createStaffForm');
        Route::post('add-staff', [StaffManagementController::class, 'storeStaff'])->name('storeStaffRecords');
    });

    Route::get('findEmailUserStaff', [StaffManagementController::class, 'findEmailUserStaff']);
    Route::get('updateEmailUserStaff', [StaffManagementController::class, 'updateEmailUserStaff']);


    Route::group(['namespace' => 'User', 'prefix' => 'vacancies/backend', 'as' => 'vacancies.backend.', 'name' => 'vacancies.backend.', 'middleware' => ['permission:manage vacancy postings']], function (){
        Route::get('/', [VacanciesBackendController::class, 'index'])->name('index');
        Route::post('/', [VacanciesBackendController::class, 'store'])->name('store');
        Route::get('/{vacancy}', [VacanciesBackendController::class, 'edit'])->name('edit');
        Route::patch('/{vacancy}', [VacanciesBackendController::class, 'update'])->name('update');
        Route::post('/{vacancy}', [VacanciesBackendController::class, 'destroy'])->name('destroy');
        Route::get('/applications/{vacancy}', [VacanciesBackendController::class, 'applications'])->name('vacancy.applications');
        Route::get('/{vacancy}/email-preview', [VacanciesBackendController::class, 'internalVacancyEmailPreview'])->name('email.preview');
        Route::post('/{vacancy}/email-processing', [VacanciesBackendController::class, 'sendEmail'])->name('email.processing');
    });

    Route::group(['namespace' => 'User', 'as' => 'job_applications.', 'prefix' => 'job_applications', 'name' => 'job_applications.'], function (){
        Route::post('/', [JobApplicationController::class, 'store'])->name('store');
        Route::get('create', [JobApplicationController::class, 'create'])->name('create');
        Route::get('job-applications/{id}', [JobApplicationController::class, 'show'])->name('show');
        Route::get('vacancies', [VacancyController::class, 'index'])->name('vacancies');
        Route::get('vacancies/{vacancy}', [VacancyController::class, 'show'])->name('show.vacancy');

        Route::resource('work_experiences', 'WorkExperienceController');

    });

    // ServiceNow
    Route::group(['namespace' => 'User', 'as' => 'service_now.', 'prefix' => 'service_now', 'name' => 'service_now.'], function (){

        Route::group(['as' => 'tickets.', 'prefix' => 'tickets', 'name' => 'tickets.'], function(){
            Route::get('/group/{group}', [TicketsController::class, 'index'])->name('index');
            Route::get('create/group/{group}', [TicketsController::class, 'create'])->name('create');
            Route::post('store', [TicketsController::class, 'store'])->name('store');
            Route::get('show/{ticket}', [TicketsController::class, 'show'])->name('show');
            Route::get('edit/{ticket}', [TicketsController::class, 'edit'])->name('edit');
            Route::post('update/{ticket}', [TicketsController::class, 'update'])->name('update');
            Route::post('delete/{ticket}', [TicketsController::class, 'destroy'])->name('delete');
            Route::post('add-log/{ticket}', [TicketsController::class, 'processAddLog'])->name('addLog');
            Route::post('stats/{group}', [TicketsController::class, 'statsApi'])->name('statsApi');
        });
    });

    // Fuel discrepancies
    Route::group(['namespace' => 'User', 'as' => 'fuel_discrepancies.', 'prefix' => 'fuel_discrepancies', 'name' => 'fuel_discrepancies.'], function (){
            Route::resource('reports', 'FuelDiscrepanciesController');
    });

    // Flight Envelope Records / Pilots
    Route::group(['namespace' => 'User', 'as' => 'flight_envelopes.', 'prefix' => 'flight_envelopes', 'name' => 'flight_envelopes.'], function (){
        Route::resource('records', 'FlightEnvelopeController');
        Route::post('cell-entry', [FlightEnvelopeController::class, 'cellEntrySaver'])->name('cellEntrySaver');
    });

    //FormEngine
    Route::group(['namespace' => 'User', 'as' => 'forms_engine.', 'prefix' => 'forms_engine', 'name' => 'forms_engine.'], function (){
        Route::group(['middleware' => ['permission:manage forms']], function(){
            Route::get('create-form', [FormsManagementController::class, 'create'])->name('create');
        });
    });

    //Ticket Glitches
    Route::group(['namespace' => 'User', 'as' => 'ticket_glitches_report.', 'prefix' => 'ticket_glitches_report', 'name' => 'ticket_glitches_report.'], function (){
        Route::group(['middleware' => ['permission:manage ticket glitches']], function(){
            Route::get('glitches-days', [TicketGlitchesController::class, 'index'])->name('index');
            Route::get('glitches-days/{day}', [TicketGlitchesController::class, 'show'])->name('show');
            Route::post('glitches-days', [TicketGlitchesController::class, 'store'])->name('store');
            Route::get('glitches-pnr', [TicketGlitchesController::class, 'editBooking'])->name('editBooking');
            Route::post('glitches-pnr', [TicketGlitchesController::class, 'updateBooking'])->name('updateBooking');
        });
    });

    Route::group(['namespace' => 'User', 'as' => 'pax_complaints.', 'prefix' => 'pax_complaints', 'middleware' => ['permission:manage pax complaints']], function (){
        Route::get('/', [PaxComplaintsController::class, 'index'])->name('index');
        Route::resource('pax_complaints', 'PaxComplaintsController');
    });

    Route::group(['namespace' => 'User', 'as' => 'business_goals.', 'prefix' => 'business_goals', 'middleware' => ['permission:manage business goals data']], function (){
        Route::get('add-report', [BusinessGoalsController::class, 'create'])->name('add_report');
        Route::get('add-single-day-report', [BusinessGoalsController::class, 'createForSingleDay'])->name('add_single_day_report');
        Route::post('add-report', [BusinessGoalsController::class, 'store'])->name('store_report');
        Route::resource('form_fields', 'ScoreCardFormFieldsController');
        Route::get('business-areas', [BusinessGoalsController::class, 'index'])->name('business.areas');
        Route::get('single-quadrant', [BusinessGoalsController::class, 'oneQuadrant'])->name('single.quadrant');
        Route::get('single-business-area', [BusinessGoalsController::class, 'singleBusinessAreaTables'])->name('single.business.area');
        Route::get('multi-business-areas', [BusinessGoalsController::class, 'multiBusinessAreaTables'])->name('multi.business.areas');
        // Dailies
        Route::get('single-daily-quadrant', [BusinessGoalsController::class, 'getSingleBSCDailyData'])->name('single.daily.quadrant');
        Route::get('single-daily-business-area', [BusinessGoalsController::class, 'singleBusinessAreaTables'])->name('single.daily.business.area');
        Route::get('multi-daily-business-areas', [BusinessGoalsController::class, 'multiBusinessAreaTables'])->name('multi.daily.business.areas');
        Route::get('group-business-areas', [BusinessGoalsController::class, 'groupBusinessAreaTables'])->name('group.business.areas');
    });

    Route::group(['namespace' => 'User', 'as' => 'flight_disruption.', 'prefix' => 'flight_disruption', 'middleware' => ['permission:manage disruption logs']], function (){
        Route::get('/', [DisruptionLogsController::class, 'index'])->name('index');
//        Route::put('/{id}', [DisruptionLogsController::class, 'update'])->name('update');
        Route::post('/', [DisruptionLogsController::class, 'store'])->name('store');
    });

    // May 20, 2024
    Route::get('finance-ra', [FinanceRaController::class, 'index'])->name('finance.ra.index')->middleware(['permission:enter finance ra logs']);
    Route::post('finance-ra', [FinanceRaController::class, 'store'])->name('finance_ra.store');

    Route::get('fuel-consumption-reports', [FuelConsumptionController::class, 'index'])->name('fuel_consumption_reports.index')->middleware(['permission:enter fuel consumption reports']);
    Route::post('fuel-consumption-reports', [FuelConsumptionController::class, 'store'])->name('fuel_consumption_report.store');

    Route::get('flight-ops-summaries', [FlightOpsSummariesController::class, 'index'])->name('flight_ops_summaries.index')->middleware(['permission:enter flight ops summaries']);
    Route::post('flight-ops-summaries', [FlightOpsSummariesController::class, 'store'])->name('flight_ops_summaries.store');

    Route::group(['namespace' => 'User', 'as' => 'aircraft_status.', 'prefix' => 'aircraft_status', 'middleware' => ['permission:manage aircraft status data']], function () {
        Route::get('aircraft-status', [AircraftStatusController::class, 'index'])->name('index');
        Route::post('aircraft-status/store', [AircraftStatusController::class, 'store'])->name('store');
    });

    Route::group(['namespace' => 'User', 'as' => 'airline_fares.', 'prefix' => 'airline_fares', 'middleware' => ['permission:manage acfa dash']], function () {
        Route::get('acfa-dash', [AcfaController::class, 'index'])->name('index');
        Route::get('acfa-trial', [AcfaController::class, 'trial'])->name('trial');
        Route::get('acfa-process', [AcfaController::class, 'processAcfaAirlines'])->name('processAcfaAirlines');

        Route::get('acfa-bg', [AcfaController::class, 'bgProcessingACFA']);
        Route::get('acfa-report-export', [AcfaController::class, 'airRmReqReport'])->name('airrm-reports');
    });

    Route::get('/user-activity-logs', [UserActivityController::class, 'index'])->middleware(['permission:view backend'])->name('user.activity.index');

    Route::get('/srb-sectors', [SRBController::class, 'structureAssessor'])->name('srb.sectors');
    Route::group(['namespace' => 'User', 'as' => 'safety_review.', 'prefix' => 'safety_review', ], function(){
        Route::group(['middleware' => ['permission:manage safety performance index']], function(){

        Route::post('objective/store', 'SRBController@storeObjective')->name('objective.store');
        Route::post('indicator/store', 'SRBController@storeIndicator')->name('indicator.store');
        Route::post('metric/store', 'SRBController@storeMetric')->name('metric.store');

        Route::put('objective/{objective}', 'SRBController@updateObjective')->name('objective.update');
        Route::put('indicator/{indicator}', 'SRBController@updateIndicator')->name('indicator.update');
        Route::put('metric/{metric}', 'SRBController@updateMetric')->name('metric.update');

        Route::delete('objective/{objective}', 'SRBController@destroyObjective')->name('objective.destroy');
        Route::delete('indicator/{indicator}', 'SRBController@destroyIndicator')->name('indicator.destroy');
        Route::delete('metric/{metric}', 'SRBController@destroyMetric')->name('metric.destroy');

        Route::get('targets', [SRBController::class, 'setPeriodTarget'])->name('targets.index');
        Route::post('targets', [SRBController::class, 'updateMetricTargets'])->name('targets.update');

        Route::post('metric-centrik-status', [SRBController::class, 'updateMetricCentrikStatus'])->name('centrik.status.update');

        Route::get('formulae', [SRBController::class, 'setMetricFormulae'])->name('formulae.index');
        Route::post('formulae', [SRBController::class, 'updateMetricFormula'])->name('formulae.update');
        Route::get('/permissions', [SpiPermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions', [SpiPermissionController::class, 'store'])->name('permissions.store');
        Route::put('/permissions/{id}', [SpiPermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{id}', [SpiPermissionController::class, 'destroy'])->name('permissions.delete');

        Route::view('report-year-selection', 'frontend.spi.report_year_selection')->name('report.year.selection');
        Route::post('years-report', [SRBController::class, 'viewReportForPeriod'])->name('years.report');

        Route::post('report-entry-centrik-confirmation', [SRBController::class, 'reportEntry'])->name('report.entry.centrik.confirm');
        });

        Route::group(['middleware' => ['permission:enter SPI data']], function() {
            Route::get('report-entry', [SRBController::class, 'reportEntry'])->name('report.entry');
            Route::post('report-entry', [SRBController::class, 'storeMetricEntry'])->name('metric.report.entry.store');
        });

    });

    Route::prefix('cug-lines')->name('cug_lines.')->group(function () {
        Route::get('/', [CugLineController::class, 'index'])->name('index');
        Route::post('/store', [CugLineController::class, 'store'])->name('store');
        Route::post('/{id}/update', [CugLineController::class, 'update'])->name('update');
        Route::post('/{id}/confirm', [CugLineController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/delete', [CugLineController::class, 'destroy'])->name('destroy');
    });

//    Route::get('bulk-insert-cug', [CugLineController::class, 'toDb']);

    Route::prefix('ftp')->name('ftp.')->group(function () {
        Route::get('/', [FtpController::class, 'index'])->name('index');
        Route::get('/listFiles', [FtpController::class, 'listFiles'])->name('listFiles');
        Route::get('/listFiles-Curl', [FtpController::class, 'ftpCurl'])->name('ftpCurl');
    });

    Route::get('pfo', function (){
        phpinfo();
    });

    Route::prefix('l_and_d_training_courses')->name('l_and_d_training_courses.')->group(function () {
        Route::get('/', [LAndDTrainingCourseController::class, 'index'])->name('index');
        Route::get('/create', [LAndDTrainingCourseController::class, 'create'])->name('create');
        Route::post('/', [LAndDTrainingCourseController::class, 'store'])->name('store');
        Route::get('/{l_and_d_training_course}/edit', [LAndDTrainingCourseController::class, 'edit'])->name('edit');
        Route::put('/{l_and_d_training_course}', [LAndDTrainingCourseController::class, 'update'])->name('update');
        Route::delete('/{l_and_d_training_course}', [LAndDTrainingCourseController::class, 'destroy'])->name('destroy');
        Route::get('/{l_and_d_training_course}', [LAndDTrainingCourseController::class, 'show'])->name('show');
    });

    Route::group(['middleware' => 'permission:manage ecs processes|manage ecs client balances'], function(){
        Route::get('ecs-dashboard', [EcsInternalDashController::class, 'index'])->name('ecs.dashboard');
        Route::get('ecs-activities', [EcsInternalDashController::class, 'ecsActivitiesLog'])->middleware('permission:view ecs activity logs')->name('ecs.activities.log');

        Route::get('ecs-reports', [EcsInternalDashController::class, 'timelyReports'])->middleware('permission:supervise ecs agents')->name('ecs.timely.reports');

        Route::prefix('ecs_portal_users')->name('ecs_portal_users.')->middleware('permission:view ecs activity logs')->group(function () {
            Route::get('/',       [EcsPortalUserAjaxController::class, 'index'])->name('index');
            Route::post('/',      [EcsPortalUserAjaxController::class, 'store'])->name('store');
            Route::put('/{id}',   [EcsPortalUserAjaxController::class, 'update'])->name('update');
            Route::delete('/{id}',[EcsPortalUserAjaxController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ecs-clients')->name('ecs_clients.')->group(function () {
            Route::get('/', [EcsClientController::class, 'index'])->name('index');
            Route::get('/create', [EcsClientController::class, 'create'])->name('create');
            Route::post('/', [EcsClientController::class, 'store'])->name('store');
            Route::get('/{ecs_client}', [EcsClientController::class, 'show'])->name('show');
            Route::get('/{ecs_client}/edit', [EcsClientController::class, 'edit'])->name('edit');
            Route::put('/{ecs_client}', [EcsClientController::class, 'update'])->name('update');
            Route::delete('/{ecs_client}', [EcsClientController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ecs_bookings')->name('ecs_bookings.')->group(function () {
            Route::get('/', [EcsBookingController::class, 'index'])->name('index');
            Route::get('/create', [EcsBookingController::class, 'create'])->middleware('permission:manage ecs processes')->name('create');
            Route::post('/create', [EcsBookingController::class, 'createBooking'])->middleware('permission:manage ecs processes')->name('selectClient');
            Route::post('/', [EcsBookingController::class, 'store'])->middleware('permission:manage ecs processes')->name('store');
//            Route::get('/{item}/edit', [EcsBookingController::class, 'edit'])->name('edit');
//            Route::put('/{item}', [EcsBookingController::class, 'update'])->name('update');
//            Route::delete('/{item}', [EcsBookingController::class, 'destroy'])->name('destroy');

            // Flight Transactions AJAX CRUD
    //        Route::get('/{item}/flight_transactions', [EcsFlightTransactionController::class, 'index'])->name('flight_transactions.index');
    //        Route::post('/{item}/flight_transactions', [EcsFlightTransactionController::class, 'store'])->name('flight_transactions.store');
    //        Route::put('/{item}/flight_transactions/{flight_transaction}', [EcsFlightTransactionController::class, 'update'])->name('flight_transactions.update');
    //        Route::delete('/{item}/flight_transactions/{flight_transaction}', [EcsFlightTransactionController::class, 'destroy'])->name('flight_transactions.destroy');
    //
    //        // Flights AJAX CRUD
    //        Route::get('/{item}/flights', [EcsFlightController::class, 'index'])->name('flights.index');
    //        Route::post('/{item}/flights', [EcsFlightController::class, 'store'])->name('flights.store');
    //        Route::put('/{item}/flights/{flight}', [EcsFlightController::class, 'update'])->name('flights.update');
    //        Route::delete('/{item}/flights/{flight}', [EcsFligh
    //tController::class, 'destroy'])->name('flights.destroy');
        });

        Route::prefix('ecs_flights_ajax')->name('ecs_flights_ajax.')->middleware('permission:manage ecs processes')->group(function () {
            Route::get('/',       [EcsFlightAjaxController::class, 'index'])->name('index');
            Route::post('/',      [EcsFlightAjaxController::class, 'store'])->name('store');
            Route::put('/{id}',   [EcsFlightAjaxController::class, 'update'])->name('update');
            Route::delete('/{id}',[EcsFlightAjaxController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ecs_flight_transactions_ajax')->middleware('permission:manage ecs processes')->name('ecs_flight_transactions_ajax.')->group(function () {
            Route::get('/',       [EcsFlightTransactionAjaxController::class, 'index'])->name('index');
            Route::post('/',      [EcsFlightTransactionAjaxController::class, 'store'])->name('store');
            Route::put('/{id}',   [EcsFlightTransactionAjaxController::class, 'update'])->name('update');
            Route::delete('/{id}',[EcsFlightTransactionAjaxController::class, 'destroy'])->name('destroy');
        });

//        Route::prefix('ecs_flights')->name('ecs_flights.')->group(function () {
//            Route::get('/', [EcsFlightController::class, 'index'])->name('index');
//            Route::get('/create', [EcsFlightController::class, 'create'])->name('create');
//            Route::post('/', [EcsFlightController::class, 'store'])->name('store');
//            Route::get('/{item}', [EcsFlightController::class, 'show'])->name('show');
//            Route::get('/{item}/edit', [EcsFlightController::class, 'edit'])->name('edit');
//            Route::put('/{item}', [EcsFlightController::class, 'update'])->name('update');
//            Route::delete('/{item}', [EcsFlightController::class, 'destroy'])->name('destroy');
//        });

        Route::prefix('ecs_reconciliations')->middleware('permission:manage ecs processes')->name('ecs_reconciliations.')->group(function () {
            Route::get('/', [EcsReconciliationController::class, 'index'])->name('index');
            Route::get('/create', [EcsReconciliationController::class, 'create'])->name('create');
            Route::post('/', [EcsReconciliationController::class, 'store'])->name('store');
            Route::get('/{item}', [EcsReconciliationController::class, 'show'])->name('show');
            Route::get('/{item}/edit', [EcsReconciliationController::class, 'edit'])->name('edit');
            Route::put('/{item}', [EcsReconciliationController::class, 'update'])->name('update');
            Route::delete('/{item}', [EcsReconciliationController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('ecs_refunds')->name('ecs_refunds.')->group(function () {
            Route::get('/', [EcsRefundController::class, 'index'])->name('index');
            Route::get('/create', [EcsRefundController::class, 'create'])->name('create');
            Route::get('/create-group-refunds', [EcsRefundController::class, 'createGroupRefunds'])->name('createGroupRefunds');
            Route::post('/', [EcsRefundController::class, 'store'])->name('store');
            Route::post('/create-group-refunds', [EcsRefundController::class, 'storeGroupRefunds'])->name('storeGroupRefunds');
            Route::get('/{item}', [EcsRefundController::class, 'show'])->name('show');
//            Route::get('/{item}/edit', [EcsRefundController::class, 'edit'])->name('edit');
//            Route::put('/{item}', [EcsRefundController::class, 'update'])->name('update');
//            Route::delete('/{item}', [EcsRefundController::class, 'destroy'])->name('destroy');
        });


        Route::prefix('ecs_client_account_summaries')->name('ecs_client_account_summaries.')->group(function () {
            Route::get('/', [EcsClientAccountSummaryController::class, 'index'])->name('index');
            Route::get('/create', [EcsClientAccountSummaryController::class, 'create'])->middleware('permission:manage ecs client balances')->name('create');
            Route::post('/', [EcsClientAccountSummaryController::class, 'store'])->middleware('permission:manage ecs client balances')->name('store');
            Route::get('/{item}', [EcsClientAccountSummaryController::class, 'show'])->name('show');
//            Route::get('/{item}/edit', [EcsClientAccountSummaryController::class, 'edit'])->name('edit');
//            Route::put('/{item}', [EcsClientAccountSummaryController::class, 'update'])->name('update');
//            Route::delete('/{item}', [EcsClientAccountSummaryController::class, 'destroy'])->name('destroy');
        });
    });

    Route::get('ecs_bookings/{ecs_booking}', [EcsBookingController::class, 'show'])->name('ecs_bookings.show');

    Route::prefix('ecs-client-portal')->name('ecs_client_portal.')->group(function () {
        Route::get('/', [EcsExternalClientController::class, 'index'])->name('dashboard');
        Route::get('account-summaries', [EcsExternalClientController::class, 'accountSummaries'])->name('accountSummaries');
        Route::get('profile', [EcsExternalClientController::class, 'clientProfile'])->name('clientProfile');
        Route::get('bookings', [EcsExternalClientController::class, 'bookings'])->name('clientBookings');
        Route::post('approve-trx/{ecs_summary}', [EcsExternalClientController::class, 'approveTrx'])->name('approveTrx');
        Route::post('dispute-trx/{ecs_summary}', [EcsExternalClientController::class, 'disputeTrx'])->name('disputeTrx');
    });

    Route::prefix('staff_travel_beneficiaries')->name('staff_travel_beneficiaries.')->group(function () {
        Route::get('/', [StaffTravelBeneficiaryController::class, 'index'])->name('index');
        Route::get('/pending', [StaffTravelBeneficiaryController::class, 'pendingBeneficiaries'])->name('pending');
        Route::get('/mine', [StaffTravelBeneficiaryController::class, 'indexMine'])->name('index.mine');
        Route::get('/create', [StaffTravelBeneficiaryController::class, 'create'])->name('create');
        Route::post('/', [StaffTravelBeneficiaryController::class, 'store'])->name('store');
        Route::get('/{item}', [StaffTravelBeneficiaryController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [StaffTravelBeneficiaryController::class, 'edit'])->name('edit');
        Route::put('/{item}', [StaffTravelBeneficiaryController::class, 'update'])->name('update');
        Route::delete('/{item}', [StaffTravelBeneficiaryController::class, 'destroy'])->name('destroy');
        Route::post('/{item}/approve', [StaffTravelBeneficiaryController::class, 'approve'])->name('approve');
        Route::post('/{item}/disapprove', [StaffTravelBeneficiaryController::class, 'disapprove'])->name('disapprove');
    });

    Route::prefix('stb_login_logs')->name('stb_login_logs.')->group(function () {
        Route::get('/', [StbLoginLogController::class, 'index'])->name('index');
//        Route::get('/create', [StbLoginLogController::class, 'create'])->name('create');
//        Route::post('/', [StbLoginLogController::class, 'store'])->name('store');
//        Route::get('/{item}', [StbLoginLogController::class, 'show'])->name('show');
//        Route::get('/{item}/edit', [StbLoginLogController::class, 'edit'])->name('edit');
//        Route::put('/{item}', [StbLoginLogController::class, 'update'])->name('update');
//        Route::delete('/{item}', [StbLoginLogController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('service_now_group_agents')->name('service_now_group_agents.')->group(function () {
        Route::get('/', [ServiceNowGroupAgentController::class, 'index'])->name('index');
        Route::get('/create', [ServiceNowGroupAgentController::class, 'create'])->name('create');
        Route::post('/', [ServiceNowGroupAgentController::class, 'store'])->name('store');
        Route::get('/{item}', [ServiceNowGroupAgentController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [ServiceNowGroupAgentController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ServiceNowGroupAgentController::class, 'update'])->name('update');
        Route::delete('/{item}', [ServiceNowGroupAgentController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('service_now_group_viewers')->name('service_now_group_viewers.')->group(function () {
        Route::get('/', [ServiceNowGroupViewerController::class, 'index'])->name('index');
        Route::get('/create', [ServiceNowGroupViewerController::class, 'create'])->name('create');
        Route::post('/', [ServiceNowGroupViewerController::class, 'store'])->name('store');
        Route::get('/{item}', [ServiceNowGroupViewerController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [ServiceNowGroupViewerController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ServiceNowGroupViewerController::class, 'update'])->name('update');
        Route::delete('/{item}', [ServiceNowGroupViewerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('legal_team_external_lawyers')->name('legal_team_external_lawyers.')->group(function () {
        Route::get('/', [LegalTeamExternalLawyerController::class, 'index'])->name('index');
        Route::get('/create', [LegalTeamExternalLawyerController::class, 'create'])->name('create');
        Route::post('/', [LegalTeamExternalLawyerController::class, 'store'])->name('store');
        Route::get('/{item}', [LegalTeamExternalLawyerController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [LegalTeamExternalLawyerController::class, 'edit'])->name('edit');
        Route::put('/{item}', [LegalTeamExternalLawyerController::class, 'update'])->name('update');
        Route::delete('/{item}', [LegalTeamExternalLawyerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('legal_team_folders')->name('legal_team_folders.')->group(function () {
        Route::get('/',       [LegalTeamFolderAjaxController::class, 'index'])->name('index');
        Route::post('/',      [LegalTeamFolderAjaxController::class, 'store'])->name('store');
        Route::get('/{id}',   [LegalTeamFolderAjaxController::class, 'show'])->name('show');
        Route::put('/{id}',   [LegalTeamFolderAjaxController::class, 'update'])->name('update');
        Route::delete('/{id}',[LegalTeamFolderAjaxController::class, 'destroy'])->name('destroy');
        route::post('access_tfm_test', [LegalTeamFolderAjaxController::class, 'fileManagerLink'])->name('fileManagerLink');

        Route::post('upload', [LegalTeamFolderAjaxController::class, 'fileUpload'])->name('fileUpload');
    });

    Route::prefix('legal_team_folder_accesses')->name('legal_team_folder_accesses.')->group(function () {
        Route::get('/',       [LegalTeamFolderAccessAjaxController::class, 'index'])->name('index');
        Route::post('/',      [LegalTeamFolderAccessAjaxController::class, 'store'])->name('store');
        Route::put('/{id}',   [LegalTeamFolderAccessAjaxController::class, 'update'])->name('update');
        Route::delete('/{id}',[LegalTeamFolderAccessAjaxController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('legal_team_documents')->name('legal_team_documents.')->group(function () {
        Route::get('/', [LegalTeamDocumentController::class, 'index'])->name('index');
        Route::get('/create', [LegalTeamDocumentController::class, 'create'])->name('create');
        Route::post('/', [LegalTeamDocumentController::class, 'store'])->name('store');
        Route::get('/{item}', [LegalTeamDocumentController::class, 'show'])->name('show');
        Route::get('/{item}/edit', [LegalTeamDocumentController::class, 'edit'])->name('edit');
        Route::put('/{item}', [LegalTeamDocumentController::class, 'update'])->name('update');
        Route::delete('/{item}', [LegalTeamDocumentController::class, 'destroy'])->name('destroy');

    });

    Route::prefix('icu_activities')->name('icu_activities.')->middleware(['permission:can enter icu activities'])->group(function () {
        Route::get('/',       [IcuActivityAjaxController::class, 'index'])->name('index');
        Route::post('/',      [IcuActivityAjaxController::class, 'store'])->name('store');
        Route::put('/{id}',   [IcuActivityAjaxController::class, 'update'])->name('update');
        Route::delete('/{id}',[IcuActivityAjaxController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('exchange_rates')->name('exchange_rates.')->middleware(['permission:can enter icu activities'])->group(function () {
        Route::get('/',       [ExchangeRateAjaxController::class, 'index'])->name('index');
        Route::post('/',      [ExchangeRateAjaxController::class, 'store'])->name('store');
        Route::put('/{id}',   [ExchangeRateAjaxController::class, 'update'])->name('update');
        Route::delete('/{id}',[ExchangeRateAjaxController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('external_vendors')->name('external_vendors.')->group(function () {
        Route::get('/',       [ExternalVendorAjaxController::class, 'index'])->name('index');
        Route::post('/',      [ExternalVendorAjaxController::class, 'store'])->name('store');
        Route::put('/{id}',   [ExternalVendorAjaxController::class, 'update'])->name('update');
        Route::delete('/{id}',[ExternalVendorAjaxController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('qa_letter')->name('qa_letter.')->group(function () {
        Route::get('/',       [QaLetterAjaxController::class, 'index'])->name('index');
        Route::post('/',      [QaLetterAjaxController::class, 'store'])->name('store');
        Route::put('/{id}',   [QaLetterAjaxController::class, 'update'])->name('update');
        Route::delete('/{id}',[QaLetterAjaxController::class, 'destroy'])->name('destroy');
    });
});
