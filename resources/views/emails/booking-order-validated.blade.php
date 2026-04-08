@php
    $booking = $bookingOrder;
    $user = $booking->user;
    $dayName = \Illuminate\Support\Carbon::parse($booking->date)->locale('id')->translatedFormat('l');
    $dateLabel = \Illuminate\Support\Carbon::parse($booking->date)->locale('id')->translatedFormat('d F Y');
    $timeLabel = \Illuminate\Support\Carbon::parse($booking->start_time)->format('H.i') . ' - ' . \Illuminate\Support\Carbon::parse($booking->end_time)->format('H.i') . ' WIB';
    $statusLabel = ucfirst($booking->status ?? 'pending');
@endphp

@if ($booking->status === 'approved')
    <p><strong>Booking Order Disetujui</strong></p>

    <p>Kepada Yth.<br>Tim Terkait / Pemohon,</p>

    <p>Dengan hormat,</p>

    <p>
        Permohonan booking fasilitas berikut telah <strong>disetujui</strong>.
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
        Fasilitas : {{ $booking->bookingType?->name ?? '-' }}<br>
        Link : {{ $booking->link ?: '-' }}
    </p>

    <p>
        Divalidasi oleh : {{ $validatorName }}
    </p>

    <p>
        Mohon agar fasilitas digunakan sesuai jadwal yang telah disetujui.
    </p>

    <p>Terima kasih atas perhatian dan kerja samanya.</p>

    <p>
        Hormat kami,<br>
        Sistem Informasi Sanbe
    </p>

    <p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
@endif

@if ($booking->status === 'rejected')
    <p><strong>Booking Order Ditolak</strong></p>

    <p>Kepada Yth.<br>Tim Terkait / Pemohon,</p>

    <p>Dengan hormat,</p>

    <p>
        Permohonan booking fasilitas berikut <strong>belum dapat disetujui</strong>.
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
        Alasan Penolakan : {{ $booking->rejection_reason ?: '-' }}
    </p>

    <p>
        Divalidasi oleh : {{ $validatorName }}
    </p>

    <p>
        Silakan melakukan penyesuaian dan mengajukan kembali apabila diperlukan.
    </p>

    <p>Terima kasih atas perhatian dan kerja samanya.</p>

    <p>
        Hormat kami,<br>
        Sistem Informasi Sanbe
    </p>

    <p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
@endif
