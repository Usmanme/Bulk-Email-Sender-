<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\web\EmailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/


require __DIR__ . DIRECTORY_SEPARATOR . 'auth.php';

Route::group([
], function () {
    Route::get('/', function () {
        return redirect()->route('login.view');
    });

    Route::get('dashboard', [DashboardController::class, 'dashboard'])->middleware('auth')->name('dashboard');
    Route::get('cachew/flush', [DashboardController::class, 'cacheFlush'])->name('cache.flush');

});

Route::group(
    ['prefix' => 'send-email', 'as' => 'send-email.'],
    function () {
        Route::get('/', [EmailController::class, 'index'])->name('index');
        Route::post('/store', [EmailController::class, 'store'])->name('store');
        Route::get('/import-email-view', [EmailController::class, 'importView'])->name('importView');
        Route::post('/import-file', [EmailController::class, 'importFile'])->name('importFile');
        Route::get('/history', [EmailController::class, 'history'])->name('history');

    }
);


Route::get('/read', [EmailController::class, 'read'])->name('read');