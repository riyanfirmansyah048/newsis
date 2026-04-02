<x-filament-panels::page>
    {{-- Page content --}}
    <x-filament::section>
        <x-slot name="heading">
            Detail BPPB
        </x-slot>

        <table>
            <tr>
                <td>Nomor BPPB</td>
                <td>:</td>
                <td>{{ $noBppb }}</td>
            </tr>
            <tr>
                <td>Type BPPB</td>
                <td>:</td>
                <td>
                    @php
                        $typeColor = match ($type_bppb_id) {
                            1 => 'primary',
                            2 => 'warning',
                            3 => 'success',
                            4 => 'danger',
                            default => 'gray',
                        };
                    @endphp

                    <x-filament::badge :color="$typeColor">
                        {{ $type_bppb }}
                    </x-filament::badge>
                </td>
            </tr>
            <tr>
                <td>Dibuat oleh</td>
                <td>:</td>
                <td>{{ $NIK }} / {{ $name }}</td>
            </tr>
            <tr>
                <td>Tanggal Dibuat</td>
                <td>:</td>
                <td>{{ $created_at?->format('d F Y') ?? '-' }}</td>
            </tr>
            <tr>
                <td>Perusahaan</td>
                <td>:</td>
                <td>{{ $company }}</td>
            </tr>
            <tr>
                <td>Regional</td>
                <td>:</td>
                <td>{{ $regional }}</td>
            </tr>
            <tr>
                <td>Bisnis Unit</td>
                <td>:</td>
                <td>{{ $businessunit }}</td>
            </tr>
            <tr>
                <td>Departemen</td>
                <td>:</td>
                <td>{{ $department }}</td>
            </tr>
            <tr>
                <td>Sub. Departemen</td>
                <td>:</td>
                <td>{{ $subdepartment }}</td>
            </tr>
            <tr>
                <td>Section</td>
                <td>:</td>
                <td>{{ $section }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $position }}</td>
            </tr>
            <tr>
                <td>Tanggal Terima Berkas</td>
                <td>:</td>
                <td>{{ $received_date ? \Illuminate\Support\Carbon::parse($received_date)->format('d F Y') : '-' }}
                </td>
            </tr>
            <tr>
                <td>Status</td>
                <td>:</td>
                <td><b><u>{{ $status }}</u></b></td>
            </tr>
        </table>
    </x-filament::section>

    {{-- Form Input --}}
    <x-filament::section>
        <form wire:submit.prevent="submit">
            {{ $this->form }}
            <br>
            <x-filament::button type="submit">
                Save changes
            </x-filament::button>
        </form>
    </x-filament::section>

    {{-- list item,ink dan software --}}
    <x-filament::section>
        <x-slot name="heading">
            List Pemesanan Barang
        </x-slot>
        @if (in_array($status_id, [1, 2, 3]) || auth()->user()->hasRole('admin'))
            <x-filament::button color="success" icon="heroicon-m-link"
                wire:click="redirectToAddBppbItem({{ $bppb_id }})">
                Add Barang
            </x-filament::button>
            <x-filament::button color="danger" icon="heroicon-m-link"
                wire:click="redirectToAddBppbInk({{ $bppb_id }})">
                Add Tinta
            </x-filament::button>
            <x-filament::button color="warning" icon="heroicon-m-link"
                wire:click="redirectToAddBppbSoftware({{ $bppb_id }})">
                Add Software
            </x-filament::button>
            <br>
        @endif

        {{-- <table>
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Qty dipesan</th>
                    <th>Qty diproses</th>
                    @if (in_array($status_id, [1, 2, 3]) || auth()->user()->hasRole('admin'))
                        <th>Action</th>
                    @endif
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
                    <td>
                        @if (in_array($status_id, [1, 2, 3]) || auth()->user()->hasRole('admin'))
                            <x-filament::button tooltip="Delete Item" color="success" icon="heroicon-m-trash"
                                onclick="confirmDelete({{ $bppb_id }}, {{ $item['item_id'] }})">
                                Delete Item
                            </x-filament::button>
                        @endif

                        <script>
                            function confirmDelete(bppbId, itemId) {
                                if (confirm('Apakah kamu yakin ingin menghapus item ini?')) {
                                    @this.call('deleteBppbItem', bppbId, itemId);
                                }
                            }
                        </script>

                    </td>
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
                    <td>
                        @if (in_array($status_id, [1, 2, 3]) || auth()->user()->hasRole('admin'))
                            <x-filament::button tooltip="Delete Tinta" color="danger" icon="heroicon-m-trash"
                                onclick="confirmDeleteInk({{ $bppb_id }}, {{ $ink['ink_id'] }})">
                                Delete Tinta
                            </x-filament::button>
                        @endif

                        <script>
                            function confirmDeleteInk(bppbId, inkId) {
                                if (confirm('Apakah kamu yakin ingin menghapus tinta ini?')) {
                                    @this.call('deleteBppbInk', bppbId, inkId);
                                }
                            }
                        </script>
                    </td>
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
                foreach ($mergedSoftware as $software) { ?>
                <tr>
                    <td>{{ $software['name'] }}</td>
                    <td>
                        <center>{{ $software['qty'] }}</center>
                    </td>
                    <td>
                        <?php
                            if ($software['qty'] == $software['qty'] - $nullCounts[$software['software_id']]) {
                            ?>
                        <center style="color: green">
                            <b>{{ $software['qty'] - $nullCounts[$software['software_id']] }}</b>
                        </center>
                        <?php
                            } else {
                            ?>
                        <center style="color: red">
                            <b>{{ $software['qty'] - $nullCounts[$software['software_id']] }}</b>
                        </center>
                        <?php
                            }
                            ?>
                    </td>
                    <td>
                        @if (in_array($status_id, [1, 2, 3]) || auth()->user()->hasRole('admin'))
                            <x-filament::button tooltip="Delete Software" color="warning" icon="heroicon-m-trash"
                                onclick="confirmDeleteSoftware({{ $bppb_id }}, {{ $software['software_id'] }})">
                                Delete Software
                            </x-filament::button>
                        @endif

                        <script>
                            function confirmDeleteSoftware(bppbId, softwareId) {
                                if (confirm('Apakah kamu yakin ingin menghapus software ini?')) {
                                    @this.call('deleteBppbSoftware', bppbId, softwareId);
                                }
                            }
                        </script>
                    </td>
                </tr>
                <?php }
                ?>
            </tbody>
        </table> --}}
        <livewire:bppb-list :bppb-id="$bppb_id" :status-id="$status_id" />

        @if (!empty($bppb_softwares))
            <br>
            <hr>
            <hr>
            <br>
            <h1>List Software</h1>

            <livewire:bppb-software-table :bppb-id="$bppb_id" :status-id="$status_id" :wire:key="'bppb-software-'.$bppb_id" />
        @endif

    </x-filament::section>

    {{-- List Purchasing Order --}}
    @if (!in_array($status_id, [1, 2, 3]))
        <x-filament::section>
            <x-slot name="heading">
                List Purchasing Order
            </x-slot>

            @if (auth()->user()->hasRole('admin'))
                <x-filament::button color="info" icon="heroicon-m-link"
                    wire:click="redirectToAddPOBppb({{ $bppb_id }})">
                    Add Purchase Order
                </x-filament::button>
            @endif

            <livewire:bppb-purchase-order-table :bppb-id="$bppb_id" />
        </x-filament::section>
    @endif

    {{-- List BPB --}}
    @if (!in_array($status_id, [1, 2, 3]) && in_array($type_bppb_id, [1, 3]))
        <x-filament::section>
            <x-slot name="heading">
                List BPB
            </x-slot>

            <livewire:bppb-bpb-table :bppb-id="$bppb_id" />
        </x-filament::section>
    @endif

    {{-- List Expedisi --}}
    @if (!in_array($status_id, [1, 2, 3]) && in_array($type_bppb_id, [2, 4]))
        <x-filament::section>
            <x-slot name="heading">
                List Expedisi
            </x-slot>

            <livewire:bppb-expedition-table :bppb-id="$bppb_id" />
        </x-filament::section>
    @endif

    <x-filament::section>
        <x-slot name="heading">
            Log BPPB
        </x-slot>

        <livewire:bppb-activity-log-table :bppb-id="$bppb_id" />
    </x-filament::section>

</x-filament-panels::page>
