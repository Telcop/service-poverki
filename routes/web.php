<?php

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::redirect('/', '/admin/reports/reestr');

Route::get('/test', [TestController::class, 'test01']); // Test
