<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Detail Software
        </x-slot>

        <table>
            <tr>
                <td>Kategori Software</td>
                <td>:</td>
                <td>{{ $record->category_software?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Merek Software</td>
                <td>:</td>
                <td>{{ $record->brand_software?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Software</td>
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

        <livewire:software-bppb-history-table :software-id="$record->id" />
    </x-filament::section>
</x-filament-panels::page>
