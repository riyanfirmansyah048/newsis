<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>

<body>
    @if (Auth::check())
        <font face='Arial, Helvetica, sans-serif'>
            <div class="container" style="width: 185mm; height: 275mm; background-color: white;">
                <table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <center>
                                <img style="width: 45%" src="{{ asset('img/sanbe-logo.gif') }}" alt="SANBE">
                                {{-- <img style="width: 45%" src="{{ public_path('img/sanbe-logo.gif') }}"> --}}
                                {{-- <img style="width: 45%" src="{{ asset('img/sanbe-logo.gif') }}"> --}}
                            </center>
                        </td>
                        <td>
                            <div align="center">
                                <h2>SANBE EMAIL CONFIG</h2>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="text-align: right;">
                            Bandung, {{ \Carbon\Carbon::parse($email->activeDate)->translatedFormat('d F Y') }}
                        </td>
                    </tr>
                </table>
                <p>
                    Kepada Yth.
                    <br>
                    <b>{{ $email->user->name }}</b>
                    <br>
                    <br>
                    <b>{{ $email->user->department->departmentName }} - {{ $email->user->position->positionName }}</b>
                    <br>
                    <b>{{ $email->user->regional->regionalName }}</b>
                    <br>
                    <b>{{ $email->user->ext }}</b>
                </p>
                <p>
                    Hal : Setting Konfigurasi Email.
                </p>
                <p>
                    Panduan Konfigurasi Mozilla Thunderbird :
                <ol>
                    <li>
                        Jalankan program Mozilla Thunderbird dengan melakukan dobel klik pada icon di desktop atau
                        memanggil langsung dari menu 'All Programs'.
                    </li>
                    <li>
                        Klik 'Create Menu Account'. Apabila sebelumnya sudah ada account email di Mozilla Thunderbird,
                        untuk menambahkan account baru Anda dapat memilih menu Tools - Account Settings - Account
                        Options - Add Email Account.
                    </li>
                    <li>
                        Masukan data :
                        <table>
                            <tr>
                                <td>Your name</td>
                                <td>:</td>
                                <td><b>{{ $email->user->name }}</b></td>
                            </tr>
                            <tr>
                                <td>Email address</td>
                                <td>:</td>
                                <td><b>{{ $email->emailName . $email->domainEmail->domainName }}</b></td>
                            </tr>
                            <tr>
                                <td>Password</td>
                                <td>:</td>
                                <td><b>{{ $email->passwordEmail }}</b></td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        Hapus centang di 'Remember password' untuk mengurangi resiko lupa password, kemudian klik tombol
                        'Continue'.
                    </li>
                    <li>
                        Tunggu sebentar pada saat proses pencarian server.
                    </li>
                    <li>
                        Pilih <b>' POP3 (keep mail on your computer) '</b>, kemudian klik tombol <b>'Manual config'</b>.
                    </li>
                    <li>
                        Pastikan data sesuai dengan data berikut :
                        <table>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Server hostname</td>
                                <td>Port</td>
                                <td>SSl</td>
                                <td>Authentication</td>
                            </tr>
                            <tr>
                                <td>Incoming:</td>
                                <td><b>POP3</b></td>
                                <td><b>{{ $email->domainEmail->imap }}</b></td>
                                <td><b>995</b></td>
                                <td><b>SSL/TLS</b></td>
                                <td><b>Normal password</b></td>
                            </tr>
                            <tr>
                                <td>Outgoing:</td>
                                <td><b>SMTP</b></td>
                                <td><b>{{ $email->domainEmail->smtp }}</b></td>
                                <td><b>465</b></td>
                                <td><b>SSL/TLS</b></td>
                                <td><b>Normal password</b></td>
                            </tr>
                            <tr>
                                <td>Username:</td>
                                <td>Incoming:</td>
                                <td><b>{{ $email->domainEmail->domainName }}</b></td>
                                <td colspan="2">Outgoing:</td>
                                <td><b>admin</b></td>
                            </tr>
                        </table>
                    </li>
                    <li>
                        Kemudian klik tombol <b>'Create Account'</b>.
                    </li>
                    <li>
                        Pada notifikasi 'Warning' centang <b>'I Understand the risks' lalu klik 'Create Account'</b>.
                    </li>
                    <li>
                        Setting Mozilla Thunderbird sudah selesai.
                    </li>
                    <li>
                        Ketika muncul dialog Outgoing server <b>(SMTP) Password Required, masukan password</b>
                        <b>{{ $email->passwordEmail }}</b>
                    </li>
                </ol>
                </p>
            </div>
        </font>
    @endif

    {{-- <script>
        window.print();
    </script> --}}
</body>

</html>
