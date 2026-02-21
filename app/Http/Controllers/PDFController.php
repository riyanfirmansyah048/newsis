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
    public function bppbPrint($id)
    {
        $bppb = Bppb::with('bppb_item', 'bppb_ink', 'bppb_software', 'user', 'status',)->find($id);
        if (!$bppb) {
            return redirect()->back()->with('error', 'Data BPPB tidak ditemukan.');
        }

        $title = 'Print BPPB - ' . ($bppb->noBppb ?? 'Unknown');

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.bppb', compact('bppb', 'title'));

        return $pdf->stream('BPPB-' . now() . '.pdf');

        // return view('pdf.bppb', compact('bppb', 'title'));
    }
    public function bpbPrint($id)
    {
        $bpb = Bpb::with('purchase_order', 'user')->find($id);
        if (!$bpb) {
            return redirect()->back()->with('error', 'Data BPPB tidak ditemukan.');
        }
        $title = 'Print BPB - ' . ($bpb->noBpb ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.bpb', compact('bpb', 'title'));

        return $pdf->stream('BPB-' . now() . '.pdf');

        // return view('pdf.bpb', compact('bpb', 'title'));
    }
    public function permohonanEmailPrint($id)
    {
        $email = Email::with('domainEmail', 'user', 'company')->find($id);
        if (!$email) {
            return redirect()->back()->with('error', 'Data Permohonan Email tidak ditemukan.');
        }
        $title = 'Print Permohonan Email - ' . ($email->user->name ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.permohonanEmail', compact('email', 'title'));

        // return $pdf->stream('Email-' . now() . '.pdf');

        return view('pdf.permohonanEmail', compact('email', 'title'));
    }

    public function konfigurasiEmailPrint($id)
    {
        $email = Email::with('domainEmail', 'user', 'company')->find($id);
        if (!$email) {
            return redirect()->back()->with('error', 'Data Konfigurasi Email tidak ditemukan.');
        }
        $title = 'Print Konfigurasi Email - ' . ($email->user->name ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.konfigurasiEmail', compact('email', 'title'));

        // return $pdf->stream('Konfigurasi Email-' . now() . '.pdf');

        return view('pdf.KonfigurasiEmail', compact('email', 'title'));
    }

    public function internet($id)
    {
        $internet = Internet::with('user')->find($id);
        if (!$internet) {
            return redirect()->back()->with('error', 'Data pengajuan Internet tidak ditemukan.');
        }
        $title = 'Print Internet - ' . ($internet->user->name ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.internet', compact('internet', 'title'));

        return $pdf->stream('Internet -' . now() . '.pdf');

        // return view('pdf.Internet', compact('internet', 'title'));
    }

    public function servicePrint($id)
    {
        $service = Service::with('user', 'item', 'serviceSolution')->find($id);
        if (!$service) {
            return redirect()->back()->with('error', 'Data Service tidak ditemukan.');
        }
        $title = 'Print Service - ' . ($service->no_service ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.service', compact('service', 'title'));

        return $pdf->stream('Service -' . now() . '.pdf');

        // return view('pdf.Service', compact('service', 'title'));
    }

    public function expeditionPrint($id)
    {
        $expedition = Expedition::with('user', 'bppb', 'expeditionDetails')->find($id);
        if (!$expedition) {
            return redirect()->back()->with('error', 'Data Expedition tidak ditemukan.');
        }

        // Tambahkan nama_barang secara manual untuk setiap detail
        foreach ($expedition->expeditionDetails as $detail) {
            $bppbId = $expedition->bppb_id;
            $typeId = $detail->type_id;

            $detail->nama_barang = match ($detail->product_form_id) {
                1, 5 => optional(Item::find($typeId))->name,
                2 => optional(Software::find($typeId))->name,
                3 => optional(Ink::find($typeId))->name,
                default => 'Tidak diketahui',
            };

            $detail->qty = match ($detail->product_form_id) {
                1, 5 => Bppb_item::where('bppb_id', $bppbId)->where('item_id', $typeId)->count(),
                2 => Bppb_software::where('bppb_id', $bppbId)->where('software_id', $typeId)->count(),
                3 => Bppb_ink::where('bppb_id', $bppbId)->where('ink_id', $typeId)->count(),
                default => 0,
            };
        }


        $title = 'Print Expedition - ' . ($expedition->noExpedition ?? 'Unknown');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.expedition', compact('expedition', 'title'));


        $expedition->datePrint = now();
        $expedition->save();

        return $pdf->stream('Expedition -' . now() . '.pdf');
        // return view('pdf.Expedition', compact('expedition', 'title'));
    }
}
