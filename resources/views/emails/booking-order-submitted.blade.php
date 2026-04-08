@php
    $booking = $bookingOrder;
    $user = $booking->user;
    $dayName = \Illuminate\Support\Carbon::parse($booking->date)->locale('id')->translatedFormat('l');
    $dateLabel = \Illuminate\Support\Carbon::parse($booking->date)->locale('id')->translatedFormat('d F Y');
    $timeLabel = \Illuminate\Support\Carbon::parse($booking->start_time)->format('H.i') . ' - ' . \Illuminate\Support\Carbon::parse($booking->end_time)->format('H.i') . ' WIB';
@endphp

<p><strong>Pengajuan Booking Order Baru</strong></p>

<p>Kepada Yth.<br>Tim Terkait / Admin,</p>

<p>Dengan hormat,</p>

<p>
    Telah diajukan permohonan booking fasilitas dengan rincian sebagai berikut:
</p>

<p>
    Nama Pemohon : {{ $user?->name ?? '-' }}<br>
    NIK : {{ $user?->NIK ?? '-' }}<br>
    Regional : {{ $user?->regional?->regionalName ?? '-' }}<br>
    Departement : {{ $user?->department?->departmentName ?? '-' }}
</p>

<p>
    Hari : {{ $dayName }}<br>
    Tanggal : {{ $dateLabel }}<br>
    Pukul : {{ $timeLabel }}<br>
    Topik / Keterangan : {{ $booking->topic }}<br>
    Host : {{ $booking->host }}<br>
    Fasilitas : {{ $booking->bookingType?->name ?? '-' }}
</p>

<p>
    Mohon agar permohonan tersebut dapat ditindaklanjuti sesuai kebutuhan yang diajukan.
</p>

<p>Terima kasih atas perhatian dan kerja samanya.</p>

<p>
    Hormat kami,<br>
    Sistem Informasi Sanbe
</p>

<p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
