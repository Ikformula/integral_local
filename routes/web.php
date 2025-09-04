<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CbtSubjectsController;
use App\Models\StaffMember;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserActivityController;

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */

// To grant a permission en masse
Route::get('/grant-hr-permissions', function () {
    $permissionId = 30;
    $modelType = 'App\Models\Auth\User';

    // Query staff from HUMAN RESOURCES and join with the users table
    $staffMembers = StaffMember::where('department_name', 'HUMAN RESOURCES')
        ->get();

    foreach ($staffMembers as $staff) {
        // Attach the permission if not already granted
        if($staff->user) {
            $userId = $staff->user->id;
            $hasPermission = DB::table('model_has_permissions')
                ->where('model_id', $userId)
                ->where('model_type', $modelType)
                ->where('permission_id', $permissionId)
                ->exists();

            if (!$hasPermission) {
                echo 'Does not have permission<br>';
            DB::table('model_has_permissions')->insert([
                'permission_id' => $permissionId,
                'model_type' => $modelType,
                'model_id' => $userId,
            ]);
            } else {
                echo 'Has permission<br>';
            }
        }else{
            echo 'No user account<br>';
        }
        // Output the staff details
        echo "Surname: {$staff->surname}, Other Names: {$staff->other_names}, Email: {$staff->email}<br><br>";
    }
});


Route::get('artisan/route-cache', function(){
    Artisan::call('route:cache');
});

//Route::view('i-f', 'iframe');

// Switch between the included languages
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__.'/frontend/');
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__.'/backend/');
});

Route::get('/csv-upload', 'CsvUploadController@showUploadForm')->name('csv.uploadForm');
Route::post('/csv-upload', 'CsvUploadController@processUpload')->name('csv.upload');
Route::post('/import-data', 'CsvUploadController@importData');

Route::group([
    'prefix' => 'cbt_data_histories',
], function () {
    Route::get('/', 'CbtDataHistoriesController@index')
        ->name('cbt_data_histories.cbt_data_history.index');
    Route::get('/create','CbtDataHistoriesController@create')
        ->name('cbt_data_histories.cbt_data_history.create');
    Route::get('/show/{cbtDataHistory}','CbtDataHistoriesController@show')
        ->name('cbt_data_histories.cbt_data_history.show');
    Route::get('/{cbtDataHistory}/edit','CbtDataHistoriesController@edit')
        ->name('cbt_data_histories.cbt_data_history.edit');
    Route::post('/', 'CbtDataHistoriesController@store')
        ->name('cbt_data_histories.cbt_data_history.store');
    Route::put('cbt_data_history/{cbtDataHistory}', 'CbtDataHistoriesController@update')
        ->name('cbt_data_histories.cbt_data_history.update');
    Route::delete('/cbt_data_history/{cbtDataHistory}','CbtDataHistoriesController@destroy')
        ->name('cbt_data_histories.cbt_data_history.destroy');
});

Route::group([
    'prefix' => 'cbt_exams',
], function () {
    Route::get('/', 'CbtExamsController@index')
        ->name('cbt_exams.cbt_exam.index');
    Route::get('/create','CbtExamsController@create')
        ->name('cbt_exams.cbt_exam.create');
    Route::get('/show/{cbtExam}','CbtExamsController@show')
        ->name('cbt_exams.cbt_exam.show');
    Route::get('/{cbtExam}/edit','CbtExamsController@edit')
        ->name('cbt_exams.cbt_exam.edit');
    Route::post('/', 'CbtExamsController@store')
        ->name('cbt_exams.cbt_exam.store');
    Route::put('cbt_exam/{cbtExam}', 'CbtExamsController@update')
        ->name('cbt_exams.cbt_exam.update');
    Route::delete('/cbt_exam/{cbtExam}','CbtExamsController@destroy')
        ->name('cbt_exams.cbt_exam.destroy');
});

Route::group([
    'prefix' => 'cbt_exam_candidates',
], function () {
    Route::get('/', 'CbtExamCandidatesController@index')
        ->name('cbt_exam_candidates.cbt_exam_candidate.index');
    Route::get('/create','CbtExamCandidatesController@create')
        ->name('cbt_exam_candidates.cbt_exam_candidate.create');
    Route::get('/show/{cbtExamCandidate}','CbtExamCandidatesController@show')
        ->name('cbt_exam_candidates.cbt_exam_candidate.show');
    Route::get('/{cbtExamCandidate}/edit','CbtExamCandidatesController@edit')
        ->name('cbt_exam_candidates.cbt_exam_candidate.edit');
    Route::post('/', 'CbtExamCandidatesController@store')
        ->name('cbt_exam_candidates.cbt_exam_candidate.store');
    Route::put('cbt_exam_candidate/{cbtExamCandidate}', 'CbtExamCandidatesController@update')
        ->name('cbt_exam_candidates.cbt_exam_candidate.update');
    Route::delete('/cbt_exam_candidate/{cbtExamCandidate}','CbtExamCandidatesController@destroy')
        ->name('cbt_exam_candidates.cbt_exam_candidate.destroy');
});

Route::group([
    'prefix' => 'cbt_exam_questions',
], function () {
    Route::get('/', 'CbtExamQuestionsController@index')
        ->name('cbt_exam_questions.cbt_exam_question.index');
    Route::get('/create','CbtExamQuestionsController@create')
        ->name('cbt_exam_questions.cbt_exam_question.create');
    Route::get('/show/{cbtExamQuestion}','CbtExamQuestionsController@show')
        ->name('cbt_exam_questions.cbt_exam_question.show');
    Route::get('/{cbtExamQuestion}/edit','CbtExamQuestionsController@edit')
        ->name('cbt_exam_questions.cbt_exam_question.edit');
    Route::post('/', 'CbtExamQuestionsController@store')
        ->name('cbt_exam_questions.cbt_exam_question.store');
    Route::put('cbt_exam_question/{cbtExamQuestion}', 'CbtExamQuestionsController@update')
        ->name('cbt_exam_questions.cbt_exam_question.update');
    Route::delete('/cbt_exam_question/{cbtExamQuestion}','CbtExamQuestionsController@destroy')
        ->name('cbt_exam_questions.cbt_exam_question.destroy');
});

Route::group([
    'prefix' => 'cbt_options',
], function () {
    Route::get('/', 'CbtOptionsController@index')
        ->name('cbt_options.cbt_option.index');
    Route::get('/create','CbtOptionsController@create')
        ->name('cbt_options.cbt_option.create');
    Route::get('/show/{cbtOption}','CbtOptionsController@show')
        ->name('cbt_options.cbt_option.show');
    Route::get('/{cbtOption}/edit','CbtOptionsController@edit')
        ->name('cbt_options.cbt_option.edit');
    Route::post('/', 'CbtOptionsController@store')
        ->name('cbt_options.cbt_option.store');
    Route::put('cbt_option/{cbtOption}', 'CbtOptionsController@update')
        ->name('cbt_options.cbt_option.update');
    Route::delete('/cbt_option/{cbtOption}','CbtOptionsController@destroy')
        ->name('cbt_options.cbt_option.destroy');
});

Route::group([
    'prefix' => 'cbt_questions',
], function () {
    Route::get('/', 'CbtQuestionsController@index')
        ->name('cbt_questions.cbt_question.index');
    Route::get('/create','CbtQuestionsController@create')
        ->name('cbt_questions.cbt_question.create');
    Route::get('/show/{cbtQuestion}','CbtQuestionsController@show')
        ->name('cbt_questions.cbt_question.show');
    Route::get('/{cbtQuestion}/edit','CbtQuestionsController@edit')
        ->name('cbt_questions.cbt_question.edit');
    Route::post('/', 'CbtQuestionsController@store')
        ->name('cbt_questions.cbt_question.store');
    Route::put('cbt_question/{cbtQuestion}', 'CbtQuestionsController@update')
        ->name('cbt_questions.cbt_question.update');
    Route::delete('/cbt_question/{cbtQuestion}','CbtQuestionsController@destroy')
        ->name('cbt_questions.cbt_question.destroy');
});

Route::group([
    'prefix' => 'cbt_question_responses',
], function () {
    Route::get('/', 'CbtQuestionResponsesController@index')
        ->name('cbt_question_responses.cbt_question_response.index');
    Route::get('/create','CbtQuestionResponsesController@create')
        ->name('cbt_question_responses.cbt_question_response.create');
    Route::get('/show/{cbtQuestionResponse}','CbtQuestionResponsesController@show')
        ->name('cbt_question_responses.cbt_question_response.show');
    Route::get('/{cbtQuestionResponse}/edit','CbtQuestionResponsesController@edit')
        ->name('cbt_question_responses.cbt_question_response.edit');
    Route::post('/', 'CbtQuestionResponsesController@store')
        ->name('cbt_question_responses.cbt_question_response.store');
    Route::put('cbt_question_response/{cbtQuestionResponse}', 'CbtQuestionResponsesController@update')
        ->name('cbt_question_responses.cbt_question_response.update');
    Route::delete('/cbt_question_response/{cbtQuestionResponse}','CbtQuestionResponsesController@destroy')
        ->name('cbt_question_responses.cbt_question_response.destroy');
});

Route::group([
    'prefix' => 'cbt_subjects', 'middleware' => ['permission:manage CBT'],
], function () {
    Route::get('/', [CbtSubjectsController::class, 'index'])
        ->name('cbt_subjects.cbt_subject.index');
    Route::get('/create',[CbtSubjectsController::class, 'create'])
        ->name('cbt_subjects.cbt_subject.create');
    Route::get('/show/{cbtSubject}',[CbtSubjectsController::class, 'show'])
        ->name('cbt_subjects.cbt_subject.show');
    Route::get('/{cbtSubject}/edit',[CbtSubjectsController::class, 'edit'])
        ->name('cbt_subjects.cbt_subject.edit');
    Route::post('/', [CbtSubjectsController::class, 'store'])
        ->name('cbt_subjects.cbt_subject.store');
    Route::put('cbt_subject/{cbtSubject}', [CbtSubjectsController::class, 'update'])
        ->name('cbt_subjects.cbt_subject.update');
    Route::delete('/cbt_subject/{cbtSubject}',[CbtSubjectsController::class, 'destroy'])
        ->name('cbt_subjects.cbt_subject.destroy');
});

Route::post('/user/activity/duration', [UserActivityController::class, 'storeDuration'])
    ->name('user.activity.duration');

