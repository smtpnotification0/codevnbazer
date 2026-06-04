<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AutoTopupController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('auto-topup/webhook', [AutoTopupController::class, 'update'])->name('auto.topup.webhook');
Route::post('humayun/webhook', [AutoTopupController::class, 'humayunWebhook'])->name('humayun.webhook');
Route::post('automation/webhook', [AutoTopupController::class, 'automationWebhook'])->name('automation.webhook');
