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
        <div>
            <font face='Arial, Helvetica, sans-serif'>
                <table style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            <center>
                                {{-- <img src="{{ asset('storage/sanbe-logo.gif') }}" alt="SANBE"> --}}
                                <img src="{{ public_path('img/sanbe-logo.gif') }}">
                                {{-- <img src="{{ asset('img/sanbe-logo.gif') }}"> --}}
                            </center>
                        </td>
                        <td>
                            <div align="center">
                                <h2>BON PERMINTAAN PEMBELIAN BARANG<br />
                                    ( BPPB )</h2>
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
                <table style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>No. BPPB</td>
                        <td>:</td>
                        <td>{{ $bppb->noBppb }}</td>
                        <td></td>
                        <td>Sub. Departement</td>
                        <td>:</td>
                        <td>{{ $bppb->user->subdepartment->subDepartmentName ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal BPPB</td>
                        <td>:</td>
                        <td>{{ $bppb->created_at->format('d F Y') }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>NIK / Pemesan</td>
                        <td>:</td>
                        <td>{{ $bppb->user->NIK }} / {{ $bppb->user->name }}</td>
                        <td></td>
                        <td>No. Phone / Ext</td>
                        <td>:</td>
                        <td>{{ $bppb->user->hp }} / {{ $bppb->user->ext }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>:</td>
                        <td>{{ $bppb->description }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
                <br>
                <table style="width: 18cm;" border="1" cellpadding="0" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="width: 12cm">Nama Barang</th>
                            <th style="width: 6cm">Banyaknya</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $items = [];
                            foreach ($bppb->bppb_item as $item) {
                                if (isset($items[$item->item->id])) {
                                    $items[$item->item->id]->qty += $item->qty;
                                } else {
                                    $items[$item->item->id] = clone $item;
                                }
                            }
                        @endphp
                        @foreach ($items as $item)
                            <tr>
                                <td>{{ $item->item->name }}</td>
                                <td>
                                    <center>{{ $item->qty }}</center>
                                </td>
                            </tr>
                        @endforeach
                        {{-- _____________________________________________________________________ --}}
                        @php
                            $inks = [];
                            foreach ($bppb->bppb_ink as $ink) {
                                if (isset($inks[$ink->ink->id])) {
                                    $inks[$ink->ink->id]->qty += $ink->qty;
                                } else {
                                    $inks[$ink->ink->id] = clone $ink;
                                }
                            }
                        @endphp
                        @foreach ($inks as $ink)
                            <tr>
                                <td>{{ $ink->ink->name }}</td>
                                <td>
                                    <center>{{ $ink->qty }}</center>
                                </td>
                            </tr>
                        @endforeach
                        {{-- _____________________________________________________________________ --}}
                        @php
                            $softwareList = [];
                            foreach ($bppb->bppb_software as $software) {
                                if (isset($softwareList[$software->software->id])) {
                                    $softwareList[$software->software->id]->qty += $software->qty;
                                } else {
                                    $softwareList[$software->software->id] = clone $software;
                                }
                            }
                        @endphp
                        @foreach ($softwareList as $software)
                            <tr>
                                <td>{{ $software->software->name }}</td>
                                <td>
                                    <center>{{ $software->qty }}</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <table style="width: 18cm;" border="1" cellpadding="0" cellspacing="0">
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
            </font>
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
                                <td>{{ $software->software->name }}</td>
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
    </body>

    </html>
@endif
