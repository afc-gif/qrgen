<?php

use App\Http\Controllers\QrController;
use Illuminate\Support\Facades\Route;

Route::get('/', [QrController::class, 'show'])->name('qr.show');
Route::post('/generate', [QrController::class, 'generate'])->name('qr.generate');
Route::get('/download', [QrController::class, 'download'])->name('qr.download');
Route::get('/reset', [QrController::class, 'reset'])->name('qr.reset');
Route::get('/generated/{path}', [QrController::class, 'file'])
    ->where('path', '.*')
    ->name('qr.file');
