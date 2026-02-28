<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/bppb/{id}/print', [PDFController::class, 'bppbPrint'])->name('bppb.print');
    Route::get('/bpb/{id}/print', [PDFController::class, 'bpbPrint'])->name('bpb.print');
    Route::get('/permohonanemail/{id}/print', [PDFController::class, 'permohonanEmailPrint'])->name('permohonanemail.print');
    Route::get('/konfigurasiemail/{id}/print', [PDFController::class, 'konfigurasiEmailPrint'])->name('konfigurasiemail.print');
    Route::get('/internet/{id}/print', [PDFController::class, 'internet'])->name('internet.print');
    Route::get('/service/{id}/print', [PDFController::class, 'servicePrint'])->name('service.print');
    Route::get('/expedition/{id}/print', [PDFController::class, 'expeditionPrint'])->name('expedition.print');

    Route::get('/service/{id}/print-surat-jalan', [PDFController::class, 'suratJalanPrint'])->name('service.print-surat-jalan');
});
