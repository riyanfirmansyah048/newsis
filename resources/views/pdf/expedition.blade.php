@if (Auth::check())
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
            font-size: 9pt;
            /* ukuran default */
        }

        table {
            font-size: 9pt;
        }

        h2 {
            font-size: 14pt;
            font-weight: bold;
        }
    </style>

    <body>
        {{-- <font face='Arial, Helvetica, sans-serif'> --}}
        <center>
            <h3>BPB Expedisi Barang</h3>
            <hr style="width: 80%;">
            <p>
                No. BPB Expedisi : {{ $expedition->noExpedition }}
                <br>
                Tanggal Print : {{ \Carbon\Carbon::parse($expedition->datePrint)->translatedFormat('d F Y') }}
            </p>
            <h4>PT.Sanbe Farma</h4>
            <hr style="width: 40%">
            <table style="width:14cm">
                <tr>
                    <td>Tanggal Kirim Barang </td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($expedition->dateStart)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td>Expeditor </td>
                    <td>:</td>
                    <td>{{ $expedition->expeditor }}</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Penerima</td>
                    <td style="vertical-align: top;">:</td>
                    <td style="vertical-align: top;">
                        <b>
                            {{ $expedition->bppb->user->NIK . ' / ' . $expedition->bppb->user->name }}
                        </b>
                        <br>
                        <b>Departement : </b>
                        {{ $expedition->bppb->user->department->departmentName ?? '' }}
                        <br>
                        <b>Region :
                        </b>{{ $expedition->bppb->user->regional->regionalName ?? '' }}
                    </td>
                </tr>
                <tr>
                    <td>Keterangan </td>
                    <td>:</td>
                    <td>{{ $expedition->description }}</td>
                </tr>
            </table>
            <br>
            <table style="width: 19cm;" border="1">
                <thead>
                    <tr>
                        <th>
                            <center>No</center>
                        </th>
                        <th>
                            <center>Barang</center>
                        </th>
                        <th>
                            <center>Qty</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($expedition->expeditionDetails as $item)
                        <tr>
                            <td>
                                <center>{{ $loop->iteration }}</center>
                            </td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>{{ $item->qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </center>
        <br>
        <center>
            <table style="width: 18cm">
                <tr>
                    <th>Yang Menyerahkan</th>
                    <th>Mengetahui</th>
                    <th>Penerima</th>
                </tr>
                <tr>
                    <td>
                        <br>
                        <br>
                        <br>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>
                        <center>(.........................................)</center>
                    </td>
                    <td>
                        <center>(.........................................)</center>
                    </td>
                    <td>
                        <center>{{ $expedition->expeditor }}</center>
                    </td>
                </tr>
                <tr>
                    <td>
                        <center>Nama Jelas</center>
                    </td>
                    <td>
                        <center>Nama Jelas</center>
                    </td>
                    <td>
                        <center>Nama Jelas</center>
                    </td>
                </tr>
            </table>
        </center>
        {{-- </font> --}}

        <script>
            window.print();
        </script>
    </body>

    </html>
@endif
