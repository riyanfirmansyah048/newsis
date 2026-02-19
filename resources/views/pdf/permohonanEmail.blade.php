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
        <table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <center>
                        {{-- <img src="{{ asset('storage/sanbe-logo.gif') }}" alt="SANBE"> --}}
                        <img src="{{ public_path('storage/sanbe-logo.gif') }}">
                    </center>
                </td>
                <td>
                    <div align="center">
                        <h2>SANBE EMAIL REQUEST FORM</h2>
                    </div>
                </td>
            </tr>
        </table>
        <br>
        <table style="width: 100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="text-align: right;">
                    Bandung, {{ $email->created_at->format('d F Y') }}
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
            <b>Hal : Permohonan Pembuatan Email Sanbe Farma</b>
        </p>
        <p style="text-align: justify;">
            Dengan hormat,
            <br>
            <br>
            Setelah membaca dan mengerti serta menyetujui aturan-aturan yang berlaku seperti yang tercantum dalam Sanbe Internal Portal, maka yang bertanda tangan dibawah ini, mengajukan permohonan pembuatan email :
        </p>
        <center>
            <table border="0">
                <tr>
                    <td>NIK </td>
                    <td>:</td>
                    <td>{{ $email->user->NIK }}</td>
                </tr>
                <tr>
                    <td>Nama </td>
                    <td>:</td>
                    <td>{{ $email->user->name }}</td>
                </tr>
                <tr>
                    <td>Departemen </td>
                    <td>:</td>
                    <td>{{ $email->user->department->departmentName }}</td>
                </tr>
                <tr>
                    <td>Jabatan </td>
                    <td>:</td>
                    <td>{{ $email->user->position->positionName }}</td>
                </tr>
                <tr>
                    <td>Lokasi </td>
                    <td>:</td>
                    <td>{{ $email->user->regional->regionalName }}</td>
                </tr>
            </table>
        </center>
        <p>
            Demikian surat permohonan dari saya. Atas perhatiannya saya ucapkan terima kasih.
        </p>
        <p>
            Approval for the e-mail account requisition :
        </p>
        <center>
            <table border="1" style="width: 100%">
                <tr>
                    <td><b><center>Requestor</center></b></td>
                    <td><b><center>Manager / Supervisor</center></b></td>
                    <td><b><center>IT Approval</center></b></td>
                    <td rowspan="3" style="vertical-align: top;"><b><center>Remarks :</center></b></td>
                </tr>
                <tr>
                    <td>
                        <br>
                        <br>
                        <br>
                        <br>
                        <center>{{ $email->user->name }}</center>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ 'Date :' . $email->created_at->format('d-m-Y') }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </center>
        <br>
        <table style="vertical-align: top;">
            <tr>
                <td style="vertical-align: top;">Note</td>
                <td style="vertical-align: top;">:</td>
                <td style="vertical-align: top; text-align: justify;">Email account yang tidak digunakan dalam jangka waktu 3 bulan akan secara otomatis dihapus oleh sistem, dan user harus melakukan registrasi ulang. Apabila Anda membutuhkan email yang dapat digunakan sebagai sarana korespondensi dengan pihak selain Sanbe Group, silahkan membuat permohonan terpisah yang ditujukan kepada Departemen IT, disertai penjelasan untuk keperluan apa saja fasilitas tersebut akan digunakan.</td>
            </tr>
        </table>
    </font>
    @endif
</body>
</html>