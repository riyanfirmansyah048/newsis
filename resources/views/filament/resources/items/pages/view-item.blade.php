<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Detail Barang
        </x-slot>

        <table>
            <tr>
                <td>Kategori</td>
                <td>:</td>
                <td>{{ $record->category?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Merek</td>
                <td>:</td>
                <td>{{ $record->brand?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Barang</td>
                <td>:</td>
                <td>{{ $record->name }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>:</td>
                <td>{{ $record->description ?: '-' }}</td>
            </tr>
        </table>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Riwayat Pemesanan BPPB
        </x-slot>

        <livewire:item-bppb-history-table :item-id="$record->id" />
    </x-filament::section>
</x-filament-panels::page>
