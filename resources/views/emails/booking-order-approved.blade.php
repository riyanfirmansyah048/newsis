@php
    $booking = $bookingOrder;
    $user = $booking->user;
    $dayName = \Illuminate\Support\Carbon::parse($booking->date)->locale('id')->translatedFormat('l');
    $dateLabel = \Illuminate\Support\Carbon::parse($booking->date)->locale('id')->translatedFormat('d F Y');
    $timeLabel = \Illuminate\Support\Carbon::parse($booking->start_time)->format('H.i') . ' WIB';
@endphp

<p>Booking Order</p>

<p>Kepada Yth.<br>Tim Terkait / Admin</p>

<p>Dengan hormat,</p>

<p>Telah diajukan permohonan booking fasilitas dengan detail sebagai berikut:</p>

<p>
    Nama Pemohon : {{ $user?->name ?? '-' }}<br>
    NIK : {{ $user?->NIK ?? '-' }}<br>
    Departement : {{ $user?->department?->departmentName ?? '-' }}
</p>

<p>
    Hari : {{ $dayName }}<br>
    Tanggal : {{ $dateLabel }}<br>
    Pukul : {{ $timeLabel }}<br>
    Topik : {{ $booking->topic }}<br>
    Host/Keterangan : {{ $booking->host }}
</p>

<p>
    Fasilitas yang dibooking:<br><br>
    {{ $booking->bookingType?->name ?? '-' }}
</p>

<p>Mohon untuk dapat diproses sesuai dengan kebutuhan yang diajukan.</p>

<p>Terima kasih atas perhatian dan kerjasamanya.</p>

<p>--<br>Email ini dikirim secara otomatis oleh sistem.</p>
