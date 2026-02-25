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
                            <h2>Pengajuan Service</h2>
                        </div>
                    </td>
                </tr>
            </table>
            <br>
            <table style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td><b>No. Service</b></td>
                                <td>:</td>
                                <td>
                                    {{ $service->noService }}
                                    <br>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    {{-- Barcode --}}
                                    {{-- <img src="data:image/png;base64,{{ base64_encode(new \Picqer\Barcode\BarcodeGeneratorPNG()->getBarcode($service->noService, \Picqer\Barcode\BarcodeGeneratorPNG::TYPE_CODE_128)) }}"
                                        alt="Barcode" style="width: 200px; height: 60px;"> --}}

                                    @php
                                        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                        $barcode = $generator->getBarcode(
                                            $service->noService,
                                            $generator::TYPE_CODE_128,
                                        );
                                    @endphp

                                    <img src="data:image/png;base64,{{ base64_encode($barcode) }}" alt="Barcode"
                                        style="width: 200px; height: 60px;">
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td><b>Pemohon</b></td>
                                <td>:</td>
                                <td>{{ $service->user->NIK . ' / ' . $service->user->name }}</td>
                            </tr>
                            <tr>
                                <td><b>Jabatan</b></td>
                                <td>:</td>
                                <td>{{ $service->user->position->positionName }}</td>
                            </tr>
                            <tr>
                                <td><b>Departement</b></td>
                                <td>:</td>
                                <td>{{ $service->user->department->departmentName }}</td>
                            </tr>
                            <tr>
                                <td><b>Sub. Departement </b></td>
                                <td>:</td>
                                <td>{{ $service->user->subdepartment->subDepartmentName }}</td>
                            </tr>
                            <tr>
                                <td><b>No. Ext</b></td>
                                <td>:</td>
                                <td>{{ $service->user->ext }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <p>
                <b>Kepada Departement :</b> IT - Sanbe
            </p>
            <center>
                <table style="width: 19cm;" border="1">
                    <tr>
                        <td style="width: 50%">
                            <center><b>Jenis Barang :</b></center>
                        </td>
                        <td style="width: 50%">
                            <center><b>Komentar / Analisa :</b></center>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <center>{{ $service->item->name }}</center>
                        </td>
                        <td>
                            <center>{{ $service->analisa }}</center>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <center><b>Masalah :</b></center>
                        </td>
                        <td>
                            <center><b>Tindakan Service :</b></center>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <center>{{ $service->problem }}</center>
                        </td>
                        <td>
                            <center>
                                @if ($service->solution_id != null)
                                    {{ $service->serviceSolution->name }}
                                @endif
                            </center>
                        </td>
                    </tr>
                </table>
            </center>
            <br>
            <br>
            <table style="width: 18cm;" border="1">
                <tr>
                    <td colspan="2">Tanggal pemohon :
                        {{ \Carbon\Carbon::parse($service->created_at)->translatedFormat('d F Y') }}</td>
                    <td colspan="2">Tanggal penerimaan :
                        {{ \Carbon\Carbon::parse($service->received_date)->translatedFormat('d F Y') }}</td>
                    <td colspan="2">Tanggal Selesai :
                        {{ \Carbon\Carbon::parse($service->finish_date)->translatedFormat('d F Y') }}</td>
                </tr>
                <tr>
                    <td style="width: 3cm">
                        <center><b>Pemohon</b></center>
                    </td>
                    <td style="width: 3cm">
                        <center><b>Atasan Pemohon</b></center>
                    </td>
                    <td style="width: 3cm">
                        <center><b>Staf IT Penerima</b></center>
                    </td>
                    <td style="width: 3cm">
                        <center><b>Staf Supervisor IT</b></center>
                    </td>
                    <td style="width: 3cm">
                        <center><b>Staf IT</b></center>
                    </td>
                    <td style="width: 3cm">
                        <center><b>Penerima</b></center>
                    </td>
                </tr>
                <tr>
                    <td><br><br><br><br><br></td>
                    <td><br><br><br><br><br></td>
                    <td><br><br><br><br><br></td>
                    <td><br><br><br><br><br></td>
                    <td><br><br><br><br><br></td>
                    <td><br><br><br><br><br></td>
                </tr>
                <tr>
                    <td style="width: 3cm">
                        <center>{{ '(' . $service->user->name . ')' }}</center>
                    </td>
                    <td style="width: 3cm">
                        <center>(__________)</center>
                    </td>
                    <td style="width: 3cm">
                        <center>(__________)</center>
                    </td>
                    <td style="width: 3cm">
                        <center>(__________)</center>
                    </td>
                    <td style="width: 3cm">
                        <center>(__________)</center>
                    </td>
                    <td style="width: 3cm">
                        <center>{{ '(' . $service->user->name . ')' }}</center>
                    </td>
                </tr>
                <tr>
                    <td style="width: 3cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 3cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 3cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 3cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 3cm">
                        <center>Nama Jelas</center>
                    </td>
                    <td style="width: 3cm">
                        <center>Nama Jelas</center>
                    </td>
                </tr>
            </table>
        </font>
    </body>

    </html>
@endif
