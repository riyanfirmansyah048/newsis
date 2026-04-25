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
            /* ukuran default */
        }

        table {
            font-size: 10pt;
        }

        h2 {
            font-size: 12pt;
            font-weight: bold;
        }
    </style>

    <body>
        {{-- <font face='Arial, Helvetica, sans-serif'> --}}
        <table style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <div align="center">
                        <u>
                            <h2>BUKTI PENERIMAAN BARANG</h2>
                        </u>
                        <h2>No : {{ $bpb->noBpb }}</h2>
                    </div>
                </td>
            </tr>
        </table>
        {{-- </font> --}}
        <table style="width: 18cm;" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td>Sesuai dengan PO (No. BPPB)</td>
                <td>:</td>
                <td><b>{{ $bpb->purchase_order->noPo . ' (' . $bpb->purchase_order->bppb->noBppb . ') ' }}</b></td>
            </tr>
            <tr>
                <td>Supplier</td>
                <td>:</td>
                <td>{{ $bpb->purchase_order->vendor->vendorName }}</td>
            </tr>
            <tr>
                <td>Department</td>
                <td>:</td>
                <td>{{ $bpb->purchase_order?->bppb?->user?->department?->departmentName ?? '-' }}</td>
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
                    $inks = [];
                    $softwares = [];
                    foreach ($bpb->purchase_order->bppb_items as $item) {
                        $itemId = $item->item->id;
                        $description = trim((string) $item->description);
                        $normalizedDescription = match ($description) {
                            '', '-', '0' => null,
                            default => $description,
                        };

                        if (isset($items[$itemId])) {
                            $items[$itemId]['qty'] += $item->qty;

                            if (
                                $normalizedDescription !== null &&
                                !in_array($normalizedDescription, $items[$itemId]['descriptions'], true)
                            ) {
                                $items[$itemId]['descriptions'][] = $normalizedDescription;
                            }
                        } else {
                            $items[$itemId] = [
                                'name' => $item->item->name,
                                'qty' => $item->qty,
                                'descriptions' => $normalizedDescription !== null ? [$normalizedDescription] : [],
                            ];
                        }
                    }

                    foreach ($bpb->purchase_order->bppb_inks as $ink) {
                        $inkId = $ink->ink->id;
                        $description = trim((string) $ink->description);
                        $normalizedDescription = match ($description) {
                            '', '-', '0' => null,
                            default => $description,
                        };

                        if (isset($inks[$inkId])) {
                            $inks[$inkId]['qty'] += $ink->qty;

                            if (
                                $normalizedDescription !== null &&
                                !in_array($normalizedDescription, $inks[$inkId]['descriptions'], true)
                            ) {
                                $inks[$inkId]['descriptions'][] = $normalizedDescription;
                            }
                        } else {
                            $inks[$inkId] = [
                                'name' => $ink->ink->name,
                                'qty' => $ink->qty,
                                'descriptions' => $normalizedDescription !== null ? [$normalizedDescription] : [],
                            ];
                        }
                    }

                    foreach ($bpb->purchase_order->bppb_softwares as $software) {
                        $softwareId = $software->software->id;
                        $description = trim((string) $software->description);
                        $normalizedDescription = match ($description) {
                            '', '-', '0' => null,
                            default => $description,
                        };

                        if (isset($softwares[$softwareId])) {
                            $softwares[$softwareId]['qty'] += $software->qty;

                            if (
                                $normalizedDescription !== null &&
                                !in_array($normalizedDescription, $softwares[$softwareId]['descriptions'], true)
                            ) {
                                $softwares[$softwareId]['descriptions'][] = $normalizedDescription;
                            }
                        } else {
                            $softwares[$softwareId] = [
                                'name' => $software->software->name,
                                'qty' => $software->qty,
                                'descriptions' => $normalizedDescription !== null ? [$normalizedDescription] : [],
                            ];
                        }
                    }
                @endphp
                @foreach ($items as $item)
                    <tr>
                        <td>
                            {{ $item['name'] }}
                            @if (
                                !empty($item['descriptions']) ||
                                    in_array('-', $item['descriptions'], true) ||
                                    in_array('0', $item['descriptions'], true))
                                {{ implode(', ', $item['descriptions']) }}
                            @endif
                        </td>
                        <td>
                            <center>{{ $item['qty'] }}</center>
                        </td>
                    </tr>
                @endforeach
                @foreach ($inks as $ink)
                    <tr>
                        <td>
                            {{ $ink['name'] }}
                            @if (
                                !empty($ink['descriptions']) ||
                                    in_array('-', $ink['descriptions'], true) ||
                                    in_array('0', $ink['descriptions'], true))
                                {{ implode(', ', $ink['descriptions']) }}
                            @endif
                        </td>
                        <td>
                            <center>{{ $ink['qty'] }}</center>
                        </td>
                    </tr>
                @endforeach
                @foreach ($softwares as $software)
                    <tr>
                        <td>
                            {{ $software['name'] }}
                            @if (
                                !empty($software['descriptions']) ||
                                    in_array('-', $software['descriptions'], true) ||
                                    in_array('0', $software['descriptions'], true))
                                {{ implode(', ', $software['descriptions']) }}
                            @endif
                        </td>
                        <td>
                            <center>{{ $software['qty'] }}</center>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        Tanggal di Terima : {{ \Carbon\Carbon::parse($bpb->dateBpb)->translatedFormat('d F Y') }}
        <br>
        <table style="width: 18cm;" border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <center>Penerima</center>
                </td>
                <td>
                    <center>Mengetahui</center>
                </td>
                <td>
                    <center>Yang Menyerahkan</center>
                </td>
            </tr>
            <tr>
                <td><br><br><br></td>
                <td><br><br><br></td>
                <td><br><br><br></td>
            </tr>
            <tr>
                <td>
                    <center>
                        {{ '(' . $bpb->purchase_order->bppb->user->name . ')' }}
                        <br>
                        {{ 'NIK : ' . $bpb->purchase_order->bppb->user->NIK }}
                    </center>
                </td>
                <td>
                    <center>
                        (..................................)
                        <br>
                        Nama Jelas
                    </center>
                </td>
                <td>
                    <center>
                        (..................................)
                        <br>
                        Nama Jelas
                    </center>
                </td>
            </tr>
        </table>
        <br>
        KETERANGAN ( Lembar putih : Keuangan, Lembar merah : Purchasing, Lembar kuning : Pemohon )

        <script>
            window.print();
        </script>
    </body>

    </html>
@endif
