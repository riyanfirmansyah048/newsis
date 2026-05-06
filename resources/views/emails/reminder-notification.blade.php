<p><strong>Reminder Expired Item</strong></p>

<p>Dengan hormat,</p>

<p>
    Ini adalah pengingat untuk item berikut yang akan / sudah mendekati masa expired.
</p>

<p>
    Nama Barang / Lisensi : {{ $reminder->item?->name ?? '-' }}<br>
    Tanggal Expired : {{ $reminder->expire_date?->translatedFormat('d F Y') ?? '-' }}<br>
    Tanggal Reminder : {{ $reminderDate->reminder_date?->translatedFormat('d F Y') ?? '-' }}
</p>

<p>
    Silakan tindak lanjuti kebutuhan ini melalui sistem jika perlu pengajuan penggantian / pembelian kembali.
</p>

<p>
    <a href="{{ $bppbUrl }}" style="display:inline-block;padding:10px 16px;background:#0ea5e9;color:#ffffff;text-decoration:none;border-radius:8px;font-weight:600;">
        Buat BPPB
    </a>
</p>

<p>Terima kasih.</p>

<p>
    Hormat kami,<br>
    Sistem Informasi Sanbe
</p>

<p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
