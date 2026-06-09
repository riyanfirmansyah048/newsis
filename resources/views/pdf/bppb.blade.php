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
            font-size: 10pt;
            line-height: 1.35;
        }

        table {
            font-size: 10pt;
            border-collapse: collapse;
        }

        h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            line-height: 1.2;
        }

        .doc-wrap {
            width: 18cm;
        }

        .mb-8 {
            margin-bottom: 8px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .mb-12 {
            margin-bottom: 12px;
        }

        .meta-table td {
            vertical-align: top;
            padding: 1px 2px;
        }

        .item-table th,
        .item-table td,
        .sign-table td {
            padding: 4px 6px;
        }
    </style>

    <body>
        <div class="doc-wrap">
            <table class="mb-10" style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 4.5cm; text-align: center;">
                        <img src="{{ public_path('img/sanbe-logo.gif') }}" alt="SANBE">
                    </td>
                    <td style="text-align: center;">
                        <h2>BON PERMINTAAN PEMBELIAN BARANG<br>( BPPB )</h2>
                    {{-- QR LINK
                    @php
                        $bppbEditUrl = rtrim((string) config('app.url'), '/') . '/sis/bppbs/' . $bppb->id . '/edit';
                        $bppbEditQr = (new \Endroid\QrCode\Builder\Builder(
                            writer: new \Endroid\QrCode\Writer\PngWriter(),
                            data: $bppbEditUrl,
                            size: 120,
                            margin: 2,
                        ))->build()->getDataUri();
                    @endphp
                    <img src="{{ $bppbEditQr }}" alt="QR Edit BPPB" style="width: 100px; height: 100px; margin-top: 6px;">
                    --}}
                    </td>
                </tr>
            </table>

            <table class="meta-table mb-8" style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 7cm; padding: 6px;">
                        <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td><b>No. BPPB</b></td>
                                <td>:</td>
                                <td>
                                    {{ $bppb->noBppb }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    @php
                                        $bppbQr = (new \Endroid\QrCode\Builder\Builder(
                                            writer: new \Endroid\QrCode\Writer\PngWriter(),
                                            data: $bppb->noBppb,
                                            size: 100,
                                            margin: 2,
                                        ))->build()->getDataUri();
                                    @endphp
                                    <div class="mb-8" style="margin-top: 4px;">
                                        <img src="{{ $bppbQr }}" alt="QR Code"
                                            style="width: 100px; height: 100px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 11cm; padding: 6px;">
                        <table style="width: 100%;" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                                <td><b>Pemohon</b></td>
                                <td>:</td>
                                <td>{{ ($bppb->user?->NIK ?? '-') . ' / ' . ($bppb->user?->name ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td><b>Jabatan</b></td>
                                <td>:</td>
                                <td>{{ $bppb->user?->position?->positionName ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><b>Departement</b></td>
                                <td>:</td>
                                <td>{{ $bppb->user?->department?->departmentName ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><b>Sub. Departement </b></td>
                                <td>:</td>
                                <td>{{ $bppb->user?->subdepartment?->subDepartmentName ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><b>No. Ext</b></td>
                                <td>:</td>
                                <td>{{ $bppb->user?->ext ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="meta-table mb-12" style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 2cm;"><b>Keterangan</b></td>
                    <td>:</td>
                    <td>{{ $bppb->description }}</td>
                </tr>
            </table>

            <table class="item-table" style="width: 18cm;" border="1" cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th style="width: 12.35cm">Nama Barang</th>
                        <th style="width: 6cm">Banyaknya</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $items = [];
                        foreach ($bppb->bppb_item as $item) {
                            if ($item->item && isset($items[$item->item->id])) {
                                $items[$item->item->id]->qty += $item->qty;
                            } elseif ($item->item) {
                                $items[$item->item->id] = clone $item;
                            }
                        }
                    @endphp
                    @foreach ($items as $item)
                        <tr>
                            <td>
                                {{ $item->item?->name ?? '[Item telah dihapus]' }}
                                @if (!empty($item->description))
                                    <br>
                                    <b>keterangan : </b> {{ $item->description }}
                                @endif
                            </td>
                            <td>
                                <center>{{ $item->qty }}</center>
                            </td>
                        </tr>
                    @endforeach
                    {{-- _____________________________________________________________________ --}}
                    @php
                        $inks = [];
                        foreach ($bppb->bppb_ink as $ink) {
                            if ($ink->ink && isset($inks[$ink->ink->id])) {
                                $inks[$ink->ink->id]->qty += $ink->qty;
                            } elseif ($ink->ink) {
                                $inks[$ink->ink->id] = clone $ink;
                            }
                        }
                    @endphp
                    @foreach ($inks as $ink)
                        <tr>
                            <td>
                                {{ $ink->ink?->name ?? '[Tinta telah dihapus]' }}
                                @if (!empty($ink->description))
                                    <br>
                                    <b>keterangan : </b> {{ $ink->description }}
                                @endif
                            </td>
                            <td>
                                <center>{{ $ink->qty }}</center>
                            </td>
                        </tr>
                    @endforeach
                    {{-- _____________________________________________________________________ --}}
                    @php
                        $softwareList = [];
                        foreach ($bppb->bppb_software as $software) {
                            if ($software->software && isset($softwareList[$software->software->id])) {
                                $softwareList[$software->software->id]->qty += $software->qty;
                            } elseif ($software->software) {
                                $softwareList[$software->software->id] = clone $software;
                            }
                        }
                    @endphp
                    @foreach ($softwareList as $software)
                        <tr>
                            <td>
                                {{ $software->software?->name ?? '[Software telah dihapus]' }}
                                @if (!empty($software->description))
                                    <br>
                                    <b>keterangan : </b> {{ $software->description }}
                                @endif
                            </td>
                            <td>
                                <center>{{ $software->qty }}</center>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <table class="sign-table" style="width: 18cm;" border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width: 6cm">
                        <center><b>Pemesan</b></center>
                    </td>
                    <td style="width: 6cm">
                        <center><b>Mengetahui</b></center>
                    </td>
                    <td style="width: 6cm">
                        <center><b>Menyetujui</b></center>
                    </td>
                </tr>
                <tr>
                    <td style="width: 6cm"><br><br><br><br><br><br></td>
                    <td style="width: 6cm"><br><br><br><br><br><br></td>
                    <td style="width: 6cm"><br><br><br><br><br><br></td>
                </tr>
                <tr>
                    <td style="width: 6cm">
                        <center>({{ $bppb->user->name }})</center>
                    </td>
                    <td style="width: 6cm">
                        <center>(.............................................)</center>
                    </td>
                    <td style="width: 6cm">
                        <center>(.............................................)</center>
                    </td>
                </tr>
                <tr>
                    <td style="width: 6cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 6cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 6cm">
                        <center>Nama Jelas</center>
                    </td>
                </tr>
            </table>
            <p>
                Tgl. diterima BPPB {{ \Carbon\Carbon::parse($bppb->received_date)->translatedFormat('d F Y') }}
            </p>
        </div>
        @if ($bppb->bppb_software !== null && count($bppb->bppb_software) > 0 && auth()->user()->hasRole('admin'))
            <div style="page-break-before: always;"></div>
            <center>
                <table style="width: 100%;" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Software</th>
                            <th>Nomor BPPB Pemohon</th>
                            <th>Pemohon IT</th>
                            <th>Pemohon (User)</th>
                            <th>Departemen Pemohon (User)</th>
                            <th>Lokasi Pemohon (User)</th>
                            <th>Serial Number</th>
                            <th>BPPB IT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bppb->bppb_software as $software)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $software->software?->name ?? '[Software telah dihapus]' }}</td>
                                <td>{{ $software->noBppbPemohon }}</td>
                                <td>{{ $software->user->name ?? ' ' }}</td>
                                <td>{{ $software->userPemohon }}</td>
                                <td>{{ $software->departementPemohon }}</td>
                                <td>{{ $software->lokasiPemohon }}</td>
                                <td>{{ $software->serialNumber }}</td>
                                <td>{{ $software->bppb->noBppb }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </center>
        @endif

        {{-- <script>
            window.print();
        </script> --}}
    </body>

    </html>
@endif
