<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\DamageReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/contact/submission', [ContactController::class, 'submission']);
Route::post('/appointment/request', [AppointmentController::class, 'request']);
Route::post('/damage-report/submission', [DamageReportController::class, 'submission']);