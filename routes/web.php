<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\TestController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/admin/reports/reestr');

Route::get('/test000', function () {
    return view('test000');
});


Route::get('download/{storage}/{num}/{y}/{m}/{d}/{filename}', [DownloadController::class, 'downloadFile'])
    ->name('downloadfile');


Route::get('/test', [TestController::class, 'test01']); // Test
