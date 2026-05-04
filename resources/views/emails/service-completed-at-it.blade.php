@php
    $applicant = $service->user;
@endphp

<p><strong>Pemberitahuan Service Selesai (Barang di IT)</strong></p>

<p>Kepada Yth.<br>{{ $applicant?->name ?? 'Pemohon' }},</p>

<p>Dengan hormat,</p>

<p>
    Pengajuan service Anda telah selesai dikerjakan di IT. Barang dapat diambil di IT sesuai prosedur yang berlaku.
</p>

<p>
    No. Service : {{ $service->noService ?? '-' }}<br>
    Nama Barang : {{ $service->item?->name ?? '-' }}<br>
    Status : {{ $service->status?->name ?? 'Selesai (Barang Di IT)' }}
</p>

<p>
    Silakan cek detail pada menu <strong>Service / Memo IT</strong> di sistem untuk informasi lebih lanjut.
</p>

<p>Terima kasih atas perhatiannya.</p>

<p>
    Hormat kami,<br>
    Sistem Informasi Sanbe
</p>

<p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
