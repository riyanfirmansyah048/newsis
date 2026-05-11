<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class ScanQrWidget extends Widget
{
    protected string $view = 'filament.widgets.scan-qr-widget';

    protected static ?int $sort = 2;

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin') ?? false;
    }
}
