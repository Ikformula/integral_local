<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\DataManagementController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('database_admin')->group(function () {
    Route::get('/', [DataManagementController::class, 'index'])->name('database_admin.index');
    Route::get('/{model}', [DataManagementController::class, 'show'])->name('database_admin.show');
    Route::get('/{model}/create', [DataManagementController::class, 'create'])->name('database_admin.create');
    Route::post('/{model}', [DataManagementController::class, 'store'])->name('database_admin.store');
    Route::get('/{model}/{id}/edit', [DataManagementController::class, 'edit'])->name('database_admin.edit');
    Route::patch('/{model}/{id}', [DataManagementController::class, 'update'])->name('database_admin.update');
    Route::delete('/{model}/{id}', [DataManagementController::class, 'destroy'])->name('database_admin.destroy');
});

