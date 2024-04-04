<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Middleware\LimitSessionPerIp;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and assigned to the "api" middleware group.
|
*/

// Close previous sessions (by-pass LimitSessionPerIp middleware)
Route::get('continue-session', [ApiController::class, 'continueSession'])->name('continue-session') ->middleware('web');

Route::group(['middleware' => ['web', LimitSessionPerIp::class]], function () {
    Route::get('test', [ApiController::class, 'test']);
});
