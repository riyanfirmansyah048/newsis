@if (Auth::check())
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>{{ $title }}</title>
    </head>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
        }

        table {
            font-size: 9pt;
        }

        h3 {
            font-size: 14pt;
            font-weight: bold;
        }
    </style>

    <body>
        <center>

            <h3>SURAT JALAN SERVICE</h3>
            <hr style="width: 80%;">

            <p>
                No. Service : {{ $service->noService }}
                <br>
                Tanggal Print : {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
            </p>

            <h4>PT. Sanbe Farma</h4>
            <hr style="width: 40%">

            <table style="width:14cm">
                <tr>
                    <td>Tanggal Kirim</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</td>
                </tr>

                <tr>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td>
                        <b>{{ $service->user->NIK }} / {{ $service->user->name }}</b>
                        <br>
                        <b>Department :</b>
                        {{ $service->user->department->departmentName ?? '' }}
                        <br>
                        <b>Regional :</b>
                        {{ $service->user->regional->regionalName ?? '' }}
                    </td>
                </tr>

                <tr>
                    <td>Vendor Tujuan</td>
                    <td>:</td>
                    <td>
                        <b>{{ $service->vendor->vendorName ?? '-' }}</b>
                    </td>
                </tr>

                <tr>
                    <td>Solusi Service</td>
                    <td>:</td>
                    <td>{{ $service->serviceSolution->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>{{ $service->analisa ?? '-' }}</td>
                </tr>
            </table>

            <br>

            <table style="width: 19cm;" border="1" cellspacing="0" cellpadding="4">
                <thead>
                    <tr>
                        <th>
                            <center>No</center>
                        </th>
                        <th>
                            <center>Detail Barang</center>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <center>1</center>
                        </td>
                        <td>
                            <b>Nama Barang :</b> {{ $service->item->name ?? '-' }}
                            <br>
                            <b>Merek :</b> {{ $service->item->brand->name ?? '-' }}
                            <br>
                            <b>Kategori :</b> {{ $service->item->category->name ?? '-' }}
                            <br>
                            <b>Serial Number :</b> {{ $service->serialNumberItem ?? '-' }}
                            <br>
                            <b>Deskripsi Masalah :</b> {{ $service->problem }}
                        </td>
                    </tr>
                </tbody>
            </table>

        </center>

        <br><br>

        <center>
            <table style="width: 18cm">
                <tr>
                    <th>Yang Menyerahkan</th>
                    <th>Mengetahui</th>
                    <th>Penerima (Vendor)</th>
                </tr>
                <tr>
                    <td><br><br><br></td>
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
                        <center>{{ $service->vendor->vendorName ?? '' }}</center>
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

        <script>
            window.print();
        </script>

    </body>

    </html>
@endif
