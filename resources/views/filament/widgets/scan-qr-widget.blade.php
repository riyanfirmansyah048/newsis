<x-filament::section>
    <x-slot name="heading">
        Scan QR Code
    </x-slot>

    <div class="space-y-4">
        <div id="qr-reader" style="display: none; width: 100%; max-width: 400px; margin: 0 auto;"></div>

        <div id="qr-result" class="hidden"></div>

        <div class="flex gap-2">
            <x-filament::button
                id="scan-qr-btn"
                icon="heroicon-o-qr-code"
                color="primary"
                onclick="window.startScanner()"
            >
                Scan QR
            </x-filament::button>

            <x-filament::button
                id="stop-scan-btn"
                style="display: none;"
                icon="heroicon-o-x-circle"
                color="danger"
                onclick="window.stopScanner()"
            >
                Batal
            </x-filament::button>
        </div>
    </div>
</x-filament::section>
