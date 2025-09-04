<?php

use App\Http\Controllers\LanguageController;

/*
 * Global Routes
 * Routes that are used between both frontend and backend.
 */
Route::group(['prefix' => 'artisan'], function(){
    Route::get('route-cache', function(){
        Artisan::call('route:cache');
        return 'done';
    });
    Route::get('optimize-force', function(){
        Artisan::call('optimize --force');
        return 'done';
    });
    Route::get('optimizers', function(){
        Artisan::call('optimize --force');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        return 'done';
    });

    Route::get('cache-clear', function(){
        Artisan::call('cache:clear');
        return 'done';
    });
    Route::get('view-clear', function(){
        Artisan::call('view:clear');
        return 'done';
    });
    Route::get('route-clear', function(){
        Artisan::call('route:clear');
        return 'done';
    });

});

Route::group(['namespace' => 'User', 'as' => 'attendance.', 'prefix' => 'attendance'], function(){
    Route::group(['middleware' => 'permission:mark attendance'], function(){
   Route::get('/', [StaffAttendanceController::class, 'index'])->name('index');
});

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
