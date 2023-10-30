<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DirectoryController;
use App\Http\Controllers\TestController;
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

        Route::post('/send', [EmailController::class, 'send'])->name('send');
        Route::get('/history', [EmailController::class, 'history'])->name('history');
        Route::post('/store', [DocumentController::class, 'store'])->name('store');
    }
);
//verification rate of emailable: 30/sec
Route::group(
    ['prefix' => 'directory', 'as' => 'directory.'],
    function () {
        
        Route::get('/delete-file/{id}', [DirectoryController::class, 'delete_file'])->name('delete-file');
        Route::get('/download-file/{id}', [DirectoryController::class, 'download_file'])->name('download-file');
        Route::delete('/delete-email/{email}', [DirectoryController::class, 'delete_email'])->name('delete-email');
        Route::get('/import-email-view', [DirectoryController::class, 'importView'])->name('importView');
        Route::get('/verify/{id}', [DirectoryController::class, 'verify'])->name('verify');
        Route::post('/import-file', [DirectoryController::class, 'importFile'])->name('importFile');
    }
);

Route::get('/batch', [TestController::class, 'batch']);
Route::get('/batch-status', [TestController::class, 'batch_status_2']);

Route::group(
    ['prefix' => 'document-upload' ,'as' => 'document-upload.'],
    function () {
        Route::get('/', [DocumentController::class, 'documentIndex'])->name('document-index');  
       
    }
);


Route::get('/test', [EmailController::class, 'test'])->name('test');

