<?php

namespace App\Http\Controllers;

use App\Models\Bpb;
use App\Models\Ink;
use App\Models\Bppb;
use App\Models\Bppb_ink;
use App\Models\Bppb_software;
use App\Models\Bppb_item;
use App\Models\Item;
use App\Models\Email;
use App\Models\Service;
use App\Models\Internet;
use App\Models\Software;
use App\Models\Expedition;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    protected function getRepeatPrintReason(): ?string
    {
        $reason = trim((string) request('reason'));

        return $reason !== '' ? $reason : null;
    }

    protected function ensureRepeatPrintReason(int $printCount, string $documentLabel)
    {
        if ($printCount < 1 || $this->getRepeatPrintReason()) {
            return null;
        }

        return redirect()
            ->back()
            ->with('error', "Alasan print ulang {$documentLabel} wajib diisi.");
    }

    protected function shouldRegisterPrint(string $documentType, int $documentId): bool
    {
        $userKey = auth()->id() ?? request()->ip();
        $sessionKey = "print_lock.{$documentType}.{$documentId}.{$userKey}";
        $now = now()->timestamp;
        $lastPrintedAt = session($sessionKey);

        if ($lastPrintedAt && ($now - (int) $lastPrintedAt) < 3) {
            return false;
        }

        session([$sessionKey => $now]);

        return true;
    }

    public function suratJalanPrint($id)
    {
        $service = Service::with([
            'user.company',
            'user.department',
            'user.position',
            'vendor',
            'item.brand',
            'item.category',
            'status',
            'serviceSolution',
        ])->find($id);

        if (!$service) {
            return redirect()->back()->with('error', 'Data Service tidak ditemukan.');
        }

        activity()
            ->performedOn($service)
            ->causedBy(auth()->user())
            ->withProperties([
                'document' => 'Surat Jalan',
                'number' => $service->noService,
                'printed_at' => now()->toDateTimeString(),
            ])
            ->log('printed');

        $title = 'Print Surat Jalan - ' . ($service->noService ?? 'Unknown');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.surat-jalan', compact('service', 'title'));

        return view('pdf.surat-jalan', compact('service', 'title'));
    }

    public function bppbPrint($id)
    {
        $bppb = Bppb::with([
            'bppb_item' => fn($q) => $q->with(['item' => fn($q2) => $q2->withTrashed()]),
            'bppb_ink' => fn($q) => $q->with(['ink' => fn($q2) => $q2->withTrashed()]),
            'bppb_software' => fn($q) => $q->with(['software' => fn($q2) => $q2->withTrashed()]),
            'user',
            'status',
        ])->find($id);
        if (!$bppb) {
            return redirect()->back()->with('error', 'Data BPPB tidak ditemukan.');
        }

        if ($response = $this->ensureRepeatPrintReason((int) $bppb->print_count, 'BPPB')) {
            return $response;
        }

        if ($this->shouldRegisterPrint('bppb', (int) $bppb->id)) {
            $bppb->increment('print_count');
            $bppb->refresh();
            $reason = $this->getRepeatPrintReason();

            activity()
                ->performedOn($bppb)
                ->causedBy(auth()->user())
                ->withProperties([
                    'document' => 'BPPB',
                    'number' => $bppb->noBppb,
                    'print_count' => $bppb->print_count,
                    'reason' => $reason,
                    'printed_at' => now()->toDateTimeString(),
                ])
                ->log('printed');
        }

        $title = 'Print BPPB - ' . ($bppb->noBppb ?? 'Unknown');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.bppb', compact('bppb', 'title'));

        return $pdf->stream('BPPB-' . now() . '.pdf');
    }
    public function bpbPrint($id)
    {
        $bpb = Bpb::with([
            'user',
            'purchase_order.vendor',
            'purchase_order.bppb.user.department',
            'purchase_order.bppb_items' => fn($q) => $q->with(['item' => fn($q2) => $q2->withTrashed()]),
            'purchase_order.bppb_inks' => fn($q) => $q->with(['ink' => fn($q2) => $q2->withTrashed()]),
            'purchase_order.bppb_softwares' => fn($q) => $q->with(['software' => fn($q2) => $q2->withTrashed()]),
        ])->find($id);
        if (!$bpb) {
            return redirect()->back()->with('error', 'Data BPPB tidak ditemukan.');
        }

        if ($response = $this->ensureRepeatPrintReason((int) $bpb->print_count, 'BPB')) {
            return $response;
        }

        if ($this->shouldRegisterPrint('bpb', (int) $bpb->id)) {
            $bpb->increment('print_count');
            $bpb->refresh();
            $reason = $this->getRepeatPrintReason();

            activity()
                ->performedOn($bpb)
                ->causedBy(auth()->user())
                ->withProperties([
                    'document' => 'BPB',
                    'number' => $bpb->noBpb,
                    'print_count' => $bpb->print_count,
                    'reason' => $reason,
                    'printed_at' => now()->toDateTimeString(),
                ])
                ->log('printed');
        }

        $title = 'Print BPB - ' . ($bpb->noBpb ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.bpb', compact('bpb', 'title'));

        return view('pdf.bpb', compact('bpb', 'title'));
    }
    public function permohonanEmailPrint($id)
    {
        $email = Email::with('domainEmail', 'user', 'company')->find($id);
        if (!$email) {
            return redirect()->back()->with('error', 'Data Permohonan Email tidak ditemukan.');
        }

        activity()
            ->performedOn($email)
            ->causedBy(auth()->user())
            ->withProperties([
                'document' => 'Permohonan Email',
                'number' => $email->id,
                'printed_at' => now()->toDateTimeString(),
            ])
            ->log('printed');

        $title = 'Print Permohonan Email - ' . ($email->user->name ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.permohonanEmail', compact('email', 'title'));

        return view('pdf.permohonanEmail', compact('email', 'title'));
    }

    public function konfigurasiEmailPrint($id)
    {
        $email = Email::with('domainEmail', 'user', 'company')->find($id);
        if (!$email) {
            return redirect()->back()->with('error', 'Data Konfigurasi Email tidak ditemukan.');
        }

        activity()
            ->performedOn($email)
            ->causedBy(auth()->user())
            ->withProperties([
                'document' => 'Konfigurasi Email',
                'number' => $email->id,
                'printed_at' => now()->toDateTimeString(),
            ])
            ->log('printed');

        $title = 'Print Konfigurasi Email - ' . ($email->user->name ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.konfigurasiEmail', compact('email', 'title'));

        return view('pdf.konfigurasiEmail', compact('email', 'title'));
    }

    public function internet($id)
    {
        $internet = Internet::with('user')->find($id);
        if (!$internet) {
            return redirect()->back()->with('error', 'Data pengajuan Internet tidak ditemukan.');
        }

        activity()
            ->performedOn($internet)
            ->causedBy(auth()->user())
            ->withProperties([
                'document' => 'Internet',
                'number' => $internet->id,
                'printed_at' => now()->toDateTimeString(),
            ])
            ->log('printed');

        $title = 'Print Internet - ' . ($internet->user->name ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.permohonanInternet', compact('internet', 'title'));

        return view('pdf.permohonanInternet', compact('internet', 'title'));
    }

    public function servicePrint($id)
    {
        $service = Service::with('user', 'item', 'serviceSolution', 'icUser')->find($id);
        if (!$service) {
            return redirect()->back()->with('error', 'Data Service tidak ditemukan.');
        }

        activity()
            ->performedOn($service)
            ->causedBy(auth()->user())
            ->withProperties([
                'document' => 'Service',
                'number' => $service->no_service,
                'printed_at' => now()->toDateTimeString(),
            ])
            ->log('printed');

        $title = 'Print Service - ' . ($service->no_service ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.service', compact('service', 'title'));

        return $pdf->stream('Service -' . now() . '.pdf');
    }

    public function expeditionPrint($id)
    {
        $expedition = Expedition::with(['user', 'bppb', 'expeditionDetails.purchaseOrder.bppb'])->find($id);
        if (!$expedition) {
            return redirect()->back()->with('error', 'Data Expedition tidak ditemukan.');
        }

        if ($response = $this->ensureRepeatPrintReason((int) $expedition->print_count, 'Expedition')) {
            return $response;
        }

        if ($this->shouldRegisterPrint('expedition', (int) $expedition->id)) {
            Expedition::whereKey($expedition->id)->increment('print_count');
            $expedition->print_count = ((int) $expedition->print_count) + 1;
            $reason = $this->getRepeatPrintReason();

            activity()
                ->performedOn($expedition)
                ->causedBy(auth()->user())
                ->withProperties([
                    'document' => 'Expedition',
                    'number' => $expedition->noExpedition,
                    'print_count' => $expedition->print_count,
                    'reason' => $reason,
                    'printed_at' => now()->toDateTimeString(),
                ])
                ->log('printed');
        }

        foreach ($expedition->expeditionDetails as $detail) {
            $bppbId = $expedition->bppb_id;
            $typeId = $detail->type_id;
            $poId = $detail->po_id;

            $detail->nama_barang = match ($detail->product_form_id) {
                1, 5 => optional(Item::find($typeId))->name,
                2 => optional(Software::find($typeId))->name,
                3 => optional(Ink::find($typeId))->name,
                default => 'Tidak diketahui',
            };

            $detail->qty = match ($detail->product_form_id) {
                1, 5 => Bppb_item::where('bppb_id', $bppbId)
                    ->where('purchase_order_id', $poId)
                    ->where('item_id', $typeId)
                    ->count(),
                2 => Bppb_software::where('bppb_id', $bppbId)
                    ->where('purchase_order_id', $poId)
                    ->where('software_id', $typeId)
                    ->count(),
                3 => Bppb_ink::where('bppb_id', $bppbId)
                    ->where('purchase_order_id', $poId)
                    ->where('ink_id', $typeId)
                    ->count(),
                default => 0,
            };

            $detail->description = match ($detail->product_form_id) {
                1, 5 => Bppb_item::where('bppb_id', $bppbId)
                    ->where('purchase_order_id', $poId)
                    ->where('item_id', $typeId)
                    ->pluck('description')
                    ->map(function ($value) {
                        $value = trim((string) $value);

                        return match ($value) {
                            '', '-', '0' => null,
                            default => $value,
                        };
                    })
                    ->filter()
                    ->unique()
                    ->implode(', '),
                2 => Bppb_software::where('bppb_id', $bppbId)
                    ->where('purchase_order_id', $poId)
                    ->where('software_id', $typeId)
                    ->pluck('description')
                    ->map(function ($value) {
                        $value = trim((string) $value);

                        return match ($value) {
                            '', '-', '0' => null,
                            default => $value,
                        };
                    })
                    ->filter()
                    ->unique()
                    ->implode(', '),
                3 => Bppb_ink::where('bppb_id', $bppbId)
                    ->where('purchase_order_id', $poId)
                    ->where('ink_id', $typeId)
                    ->pluck('description')
                    ->map(function ($value) {
                        $value = trim((string) $value);

                        return match ($value) {
                            '', '-', '0' => null,
                            default => $value,
                        };
                    })
                    ->filter()
                    ->unique()
                    ->implode(', '),
                default => '',
            };
        }

        $title = 'Print Expedition - ' . ($expedition->noExpedition ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.expedition', compact('expedition', 'title'));


        $expedition->datePrint = now();
        $expedition->save();

        return view('pdf.expedition', compact('expedition', 'title'));
    }
}
