<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\QrScanController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/qr-scan/search', [QrScanController::class, 'search'])->name('qr-scan.search');
    Route::get('/bppb/{id}/print', [PDFController::class, 'bppbPrint'])->name('bppb.print');
    Route::get('/bpb/{id}/print', [PDFController::class, 'bpbPrint'])->name('bpb.print');
    Route::get('/permohonanemail/{id}/print', [PDFController::class, 'permohonanEmailPrint'])->name('permohonanemail.print');
    Route::get('/konfigurasiemail/{id}/print', [PDFController::class, 'konfigurasiEmailPrint'])->name('konfigurasiemail.print');
    Route::get('/internet/{id}/print', [PDFController::class, 'internet'])->name('internet.print');
    Route::get('/service/{id}/print', [PDFController::class, 'servicePrint'])->name('service.print');
    Route::get('/expedition/{id}/print', [PDFController::class, 'expeditionPrint'])->name('expedition.print');

    Route::get('/service/{id}/print-surat-jalan', [PDFController::class, 'suratJalanPrint'])->name('service.print-surat-jalan');

    Route::get('/booking-orders/calendar-data', function (\Illuminate\Http\Request $request) {
        $query = App\Models\BookingOrder::with(['user', 'bookingType', 'assignedUnit'])->withTrashed();

        if ($request->filled('booking_type_id')) {
            $query->where('booking_type_id', $request->booking_type_id);
        }

        if ($request->filled('start')) {
            $query->whereDate('date', '>=', $request->start);
        }

        if ($request->filled('end')) {
            $query->whereDate('date', '<=', $request->end);
        }

        $statusColors = [
            'pending' => '#f59e0b',
            'approved' => '#10b981',
            'rejected' => '#ef4444',
        ];

        $statusLabels = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ];

        return $query->get()->map(function ($booking) use ($statusColors, $statusLabels) {
            $startDateTime = $booking->date->format('Y-m-d') . 'T' . $booking->start_time;
            $endDateTime = $booking->date->format('Y-m-d') . 'T' . $booking->end_time;

            return [
                'id' => $booking->id,
                'title' => $booking->topic . ' - ' . ($booking->user?->name ?? 'Unknown'),
                'start' => $startDateTime,
                'end' => $endDateTime,
                'color' => $statusColors[$booking->status] ?? '#6b7280',
                'textColor' => '#ffffff',
                'url' => url("/sis/booking-orders/{$booking->id}/edit"),
                'extendedProps' => [
                    'status' => $booking->status,
                    'status_text' => $statusLabels[$booking->status] ?? $booking->status,
                    'host' => $booking->host,
                    'unit' => $booking->assignedUnit?->name ?? '-',
                    'time' => substr($booking->start_time, 0, 5) . ' - ' . substr($booking->end_time, 0, 5),
                    'booking_type' => $booking->bookingType?->name ?? '-',
                ],
            ];
        });
    })->name('booking-orders.calendar-data');
});
