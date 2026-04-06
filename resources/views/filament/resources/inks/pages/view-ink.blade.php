<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Detail Tinta
        </x-slot>

        <table>
            <tr>
                <td>Kategori Tinta</td>
                <td>:</td>
                <td>{{ $record->category_ink?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Merek Tinta</td>
                <td>:</td>
                <td>{{ $record->brand_ink?->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Nama Tinta</td>
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

        <livewire:ink-bppb-history-table :ink-id="$record->id" />
    </x-filament::section>
</x-filament-panels::page>
