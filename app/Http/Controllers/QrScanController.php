<?php

namespace App\Http\Controllers;

use App\Models\Bppb;
use App\Models\Service;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\Bppbs\BppbResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QrScanController extends Controller
{
    public function search(Request $request)
    {
        $code = trim($request->input('code'));

        if (empty($code)) {
            return response()->json(['found' => false, 'message' => 'Kode kosong']);
        }

        $service = Service::where('noService', $code)->first();
        if ($service) {
            return response()->json([
                'found' => true,
                'editUrl' => ServiceResource::getUrl('edit', ['record' => $service->id]),
            ]);
        }

        $bppb = Bppb::where('noBppb', $code)->first();
        if ($bppb) {
            return response()->json([
                'found' => true,
                'editUrl' => BppbResource::getUrl('edit', ['record' => $bppb->id]),
            ]);
        }

        return response()->json(['found' => false, 'message' => 'Record tidak ditemukan']);
    }
}
