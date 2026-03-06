@if (Auth::check())
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>{{ $title }}</title>
    </head>

    <body>
        <font face='Arial, Helvetica, sans-serif'>
            <div class="container" style="width: 185mm; height: 275mm; background-color: white;">
                <table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <center>
                                <img src="{{ asset('img/sanbe-logo.gif') }}" alt="SANBE">
                                {{-- <img src="{{ public_path('img/sanbe-logo.gif') }}"> --}}
                                {{-- <img src="{{ asset('img/sanbe-logo.gif') }}"> --}}
                            </center>
                        </td>
                        <td>
                            <div align="center">
                                <h2>
                                    INTERNET REQUEST KARYAWAN</h2>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td style="text-align: right;">
                            Bandung, {{ $internet->created_at->format('d F Y') }}
                        </td>
                    </tr>
                </table>
                <p>
                    <b>Kepada Yth.</b>
                    <br>
                    Reza Ferdinansyah
                    <br>
                    IT-Manager
                    <br>
                    Sanbe Tower II Lantai 3
                    <br>
                    Jl.Tamansari No.10
                    <br>
                    <br>
                    <b>Hal : Permohonan Akses Internet</b>
                </p>
                <p style="text-align: justify;">
                    Dengan hormat,
                    <br>
                    <br>
                    Setelah membaca dan mengerti serta menyetujui aturan-aturan yang berlaku seperti yang tercantum
                    dalam Sanbe Internal Sistem, maka yang bertanda tangan dibawah ini, mengajukan permohonan pembuatan
                    Akses Internet :
                </p>
                <center>
                    <table border="0">
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>{{ $internet->user->NIK }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td>:</td>
                            <td>{{ $internet->user->name }}</td>
                        </tr>
                        <tr>
                            <td>Departemen </td>
                            <td>:</td>
                            <td>{{ $internet->user->department->departmentName }}</td>
                        </tr>
                        <tr>
                            <td>Jabatan </td>
                            <td>:</td>
                            <td>{{ $internet->user->position->positionName }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi </td>
                            <td>:</td>
                            <td>{{ $internet->user->regional->regionalName }}</td>
                        </tr>
                        <tr>
                            <td>IP-Address</td>
                            <td>:</td>
                            <td>{{ $internet->ip }}</td>
                        </tr>
                    </table>
                </center>
                <p style="text-align: justify;">
                    Akses Internet Dipergunakan untuk :
                    <br>
                    <b>
                        {{ $internet->description }}
                    </b>
                </p>
                <p style="text-align: justify;">
                    Alamat URL Internet Yang Akan Dibuka :
                    <br>
                    <b>
                        {{ $internet->url }}
                    </b>
                </p>
                <p style="text-align: justify;">
                    Demikian surat permohonan dari saya. Atas perhatiannya saya ucapkan terima kasih.
                    <br>
                    Approval for the Internet Access :
                </p>
                <center>
                    <table border="1" style="width: 100%">
                        <tr>
                            <td style="width: 25%;">
                                <center><b>Requestor</b></center>
                            </td>
                            <td style="width: 25%;">
                                <center><b>Manager / Supervisor</b></center>
                            </td>
                            <td style="width: 25%;">
                                <center><b>Owner</b></center>
                            </td>
                            <td style="width: 25%;">
                                <center><b>IT Approval</b></center>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <center>
                                    <br>
                                    <br>
                                    <br>
                                    <br>
                                    {{ $internet->user->name }}
                                </center>
                            </td>
                            <td></td>
                            <td>
                                <br>
                                <br>
                                <br>
                                <br>
                                <center>
                                    Drs.Jahja Santoso, Pharmacist.
                                </center>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                Date :
                                <br>
                                {{ $internet->created_at->format('d F Y') }}
                            </td>
                            <td>Date :</td>
                            <td>Date :</td>
                            <td>Date :</td>
                        </tr>
                    </table>
                </center>
            </div>
        </font>

        <script>
            window.print();
        </script>
    </body>

    </html>
@endif
