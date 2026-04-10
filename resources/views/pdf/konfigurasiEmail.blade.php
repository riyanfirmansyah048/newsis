<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
</head>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 10pt;
        /* ukuran default */
    }

    table {
        font-size: 10pt;
    }

    h2 {
        font-size: 14pt;
        font-weight: bold;
    }
</style>

<body>
    @if (Auth::check())
        {{-- <font face='Arial, Helvetica, sans-serif'> --}}
        <div class="container" style="width: 185mm; height: 275mm; background-color: white;">
            <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        <table style="width: 70%; margin: 0 auto;" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td style="width: 32%; text-align: center; vertical-align: middle;">
                                    @if ($email->idCompany == 1)
                                        <img style="width: 65%;" src="{{ asset('img/sanbe-logo.gif') }}" alt="SANBE">
                                    @elseif ($email->idCompany == 2)
                                        <img style="width: 85%;" src="{{ asset('img/capri-logo.jpg') }}" alt="CAPRI">
                                    @elseif ($email->idCompany == 3)
                                        <img style="width: 65%;" src="{{ asset('img/sanbe-logo.gif') }}" alt="SANBE">
                                    @elseif ($email->idCompany == 4)
                                        <img style="width: 65%;" src="{{ asset('img/sanbe-logo.gif') }}" alt="SANBE">
                                    @elseif ($email->idCompany == 5)
                                        <img style="width: 85%;" src="{{ asset('img/graha-logo.png') }}" alt="GRAHA">
                                    @else
                                        <img style="width: 65%;" src="{{ asset('img/sanbe-logo.gif') }}" alt="SANBE">
                                    @endif
                                </td>
                                <td style="width: 68%; text-align: center; vertical-align: middle;">
                                    @if ($email->idCompany == 1)
                                        <h2 style="margin: 0; text-align: center;">
                                            {{ $email->company->companyName }}<br>EMAIL CONFIG FORM</h2>
                                    @elseif ($email->idCompany == 2)
                                        <h2 style="margin: 0; text-align: center;">
                                            {{ $email->company->companyName }}<br>EMAIL CONFIG FORM</h2>
                                    @elseif ($email->idCompany == 3)
                                        <h2 style="margin: 0; text-align: center;">
                                            {{ $email->company->companyName }}<br>EMAIL CONFIG FORM</h2>
                                    @elseif ($email->idCompany == 4)
                                        <h2 style="margin: 0; text-align: center;">
                                            {{ $email->company->companyName }}<br>EMAIL CONFIG FORM</h2>
                                    @elseif ($email->idCompany == 5)
                                        <h2 style="margin: 0; text-align: center;">
                                            {{ $email->company->companyName }}<br>EMAIL CONFIG FORM</h2>
                                    @else
                                        <h2 style="margin: 0; text-align: center;">SANBE EMAIL CONFIG FORM</h2>
                                    @endif
                                </td>
                            </tr>
                        </table>
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
                    {{-- <b>{{ $email->passwordEmail }}</b> --}}
                    <b>S@nbe2019</b>
                </li>
            </ol>
            </p>
        </div>
        {{-- </font> --}}
    @endif

    <script>
        window.print();
    </script>
</body>

</html>
