<!-- resources/views/filament/components/info-bppb.blade.php -->
@vite('resources/css/app.css')
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td><strong>Nomor BPPB</strong></td>
        <td>:</td>
        <td>{{ $noBppb }}</td>
    </tr>
    <tr>
        <td><strong>Dibuat oleh</strong></td>
        <td>:</td>
        <td>{{ $NIK.' / '.$name }}</td>
    </tr>

    <tr>
        <td><strong>Tanggal Dibuat</strong></td>
        <td>:</td>
        <td>
            <?php
            if ($created_at != '') { ?>
                {{ $created_at->format('d F Y') }}
            <?php
            } else {
                echo '';
            }
            ?>
        </td>
    </tr>
    <tr>
        <td><strong>Perusahaan</strong></td>
        <td>:</td>
        <td>{{ $company }}</td>
    </tr>
    <tr>
        <td><strong>Regional</strong></td>
        <td>:</td>
        <td>{{ $regional }}</td>
    </tr>
    <tr>
        <td><strong>Bisnis Unit</strong></td>
        <td>:</td>
        <td>{{ $businessunit }}</td>
    </tr>
    <tr>
        <td><strong>Departement</strong></td>
        <td>:</td>
        <td>{{ $businessunit }}</td>
    </tr>
    <tr>
        <td><strong>Sub. Departement</strong></td>
        <td>:</td>
        <td>{{ $subdepartment }}</td>
    </tr>
    <tr>
        <td><strong>Section</strong></td>
        <td>:</td>
        <td>{{ $section }}</td>
    </tr>
    <tr>
        <td><strong>Jabatan</strong></td>
        <td>:</td>
        <td>{{ $position }}</td>
    </tr>

    <tr>
        <td><strong>Tanggal Terima Berkas</strong></td>
        <td>:</td>
        <td>
            <?php
            if ($received_date != '') { ?>
                {{ \Carbon\Carbon::parse($received_date)->format('d F Y') }}
            <?php
            } else {
                echo '-';
            }
            ?>
        </td>
    </tr>

    <tr>
        <td><strong>Status</strong></td>
        <td>:</td>
        <td><b><u>{{ $status }}</u></b></td>
    </tr>
</table>
<br>
<hr>
<br>
<!-- Tombol Create Barang -->
{{-- <div class="mt-4">
    <button onclick="openItemModal()" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
        Create Barang
    </button>
    <button onclick="openInkModal()" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
        Create Tinta
    </button>
    <button onclick="openSoftwareModal()" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
        Create Software
    </button>
</div> --}}

{{-- start modal create --}}
{{-- end modal create --}}

<table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr>
            <th style="text-align: left;">Nama Item</th>
            <th>Qty dipesan</th>
            <th>Qty diproses</th>
            {{-- <th>Action</th> --}}
        </tr>
    </thead>
    <tbody>
        <?php
        $mergedItems = [];
        $nullCounts = [];

        foreach ($bppb_items as $item) {
            if (isset($mergedItems[$item['item_id']])) {
                $mergedItems[$item['item_id']]['qty'] += $item['qty'];
            } else {
                $mergedItems[$item['item_id']] = $item;
                $nullCounts[$item['item_id']] = 0;
            }

            if ($item['purchase_order_id'] === null) {
                $nullCounts[$item['item_id']] += 1;
            }
        }
        foreach ($mergedItems as $item) { ?>
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>
                    <center>{{ $item['qty'] }}</center>
                </td>
                <td>
                    <?php
                    if ($item['qty'] == $item['qty'] - $nullCounts[$item['item_id']]) {
                    ?>
                        <center style="color: green"><b>{{ $item['qty'] - $nullCounts[$item['item_id']] }}</b></center>
                    <?php
                    } else {
                    ?>
                        <center style="color: red"><b>{{ $item['qty'] - $nullCounts[$item['item_id']] }}</b></center>
                    <?php
                    }
                    ?>
                </td>
                {{-- <td class="py-2">
                    <center>
                        <a href="#" 
                            onclick="return confirm('Are you sure you want to delete this item?')" 
                            class="py-1 px-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition my-2">
                            Delete
                        </a>
                    </center>
                </td> --}}
            </tr>
        <?php }
        ?>

        <?php
        $mergedInks = [];
        $nullCounts = [];

        foreach ($bppb_inks as $ink) {
            if (isset($mergedInks[$ink['ink_id']])) {
                $mergedInks[$ink['ink_id']]['qty'] += $ink['qty'];
            } else {
                $mergedInks[$ink['ink_id']] = $ink;
                $nullCounts[$ink['ink_id']] = 0;
            }

            if ($ink['purchase_order_id'] === null) {
                $nullCounts[$ink['ink_id']] += 1;
            }
        }
        foreach ($mergedInks as $ink) { ?>
            <tr>
                <td>{{ $ink['name'] }}</td>
                <td>
                    <center>{{ $ink['qty'] }}</center>
                </td>
                <td>
                    <?php
                    if ($ink['qty'] == $ink['qty'] - $nullCounts[$ink['ink_id']]) {
                    ?>
                        <center style="color: green"><b>{{ $ink['qty'] - $nullCounts[$ink['ink_id']] }}</b></center>
                    <?php
                    } else {
                    ?>
                        <center style="color: red"><b>{{ $ink['qty'] - $nullCounts[$ink['ink_id']] }}</b></center>
                    <?php
                    }
                    ?>
                </td>
                {{-- <td>
                    <center>
                        Tombol
                    </center>
                </td> --}}
            </tr>
        <?php }
        ?>

        <?php
        $mergedSoftware = [];
        $nullCounts = [];

        foreach ($bppb_softwares as $software) {
            if (isset($mergedSoftware[$software['software_id']])) {
                $mergedSoftware[$software['software_id']]['qty'] += $software['qty'];
            } else {
                $mergedSoftware[$software['software_id']] = $software;
                $nullCounts[$software['software_id']] = 0;
            }

            if ($software['purchase_order_id'] === null) {
                $nullCounts[$software['software_id']] += 1;
            }
        }
        foreach ($mergedSoftware as $software) {
            $processed = $software['qty'] - $nullCounts[$software['software_id']];
        ?>
            <tr>
                <td>{{ $software['name'] }}</td>
                <td>
                    <center>{{ $software['qty'] }}</center>
                </td>
                <td>
                    <?php
                    if ($software['qty'] == $processed) {
                    ?>
                        <center style="color: green"><b>{{ $processed }}</b></center>
                    <?php
                    } else {
                    ?>
                        <center style="color: red"><b>{{ $processed }}</b></center>
                    <?php
                    }
                    ?>
                </td>
                {{-- <td>
                    <center>
                        Tombol
                    </center>
                </td> --}}
            </tr>
        <?php }
        ?>
    </tbody>
</table>
<br>
<hr>
<br>
<!-- Tombol Create PO -->
{{-- <div class="mt-4">
    <button onclick="openPOModal()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
        Create PO
    </button>
</div> --}}
<br>
<table border="1" style="width: 100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="text-align: left;">No. Purchase Order</th>
            <th style="text-align: left;">Vendor</th>
            <th style="text-align: left;">Tanggal Purchase Order</th>
            {{-- <th style="text-align: left;"><center>Action</center></th> --}}
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($purchase_orders as $po) { ?>
            <tr>
                <td>{{ $po['noPo'] }}</td>
                <td>{{ $po['vendor'] }}</td>
                <td>{{ \Carbon\Carbon::parse($po['datePo'])->format('d F Y') }}</td>
                {{-- <td class="px-4 py-2">
                    <?php
                    $bpbFound = false;
                    foreach ($bpb as $itembpb) {
                        if ($itembpb['po_id'] == $po['id']) {
                            $bpbFound = true;
                            break;
                        }
                    }
                    ?>
                    <center>
                        @if ($bpbFound)
                            <a href="{{ route('bpb.print', ['id' => $itembpb['id']]) }}" 
                                class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                                Print BPB
                            </a>
                        @else
                            <a href="{{ route('filament.admin.resources.bpbs.create', ['po_id' => $po['id'], 'bppb_id' => $po['bppb_id']]) }}" 
                                class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                                Create BPB
                            </a>
                        @endif
                    </center>
                </td> --}}
            </tr>
        <?php }
        ?>
    </tbody>
</table>
<hr>

<script>
    function openItemModal() {
        document.getElementById('itemModal').classList.remove('hidden');
    }

    function closeItemModal() {
        document.getElementById('itemModal').classList.add('hidden');
    }
</script>