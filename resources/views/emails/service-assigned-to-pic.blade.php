@php
    $applicant = $service->user;
@endphp

<p><strong>Penugasan Service / Memo IT</strong></p>

<p>Kepada Yth.<br>{{ $service->icUser?->name ?? 'PIC' }},</p>

<p>Dengan hormat,</p>

<p>
    Anda ditetapkan sebagai PIC untuk pengajuan service berikut:
</p>

<p>
    No. Service : {{ $service->noService ?? '-' }}<br>
    Nama Pemohon : {{ $applicant?->name ?? '-' }}<br>
    NIK : {{ $applicant?->NIK ?? '-' }}<br>
    Nama Barang : {{ $service->item?->name ?? '-' }}<br>
    Status : {{ $service->status?->name ?? '-' }}
</p>

<p>
    Mohon ditindaklanjuti melalui menu <strong>Service / Memo IT</strong> pada sistem.
</p>

<p>Terima kasih atas perhatian dan kerja samanya.</p>

<p>
    Hormat kami,<br>
    Sistem Informasi Sanbe
</p>

<p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
