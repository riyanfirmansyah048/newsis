<?php

namespace App\Filament\Widgets;

use App\Models\Service;
use Filament\Schemas\Components\Section;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class ServicePicStats extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = Auth::user();
        if (! $user?->can('access-all-service')) {
            return [];
        }

        $totalPic = Service::whereNotNull('ic_id')
            ->distinct('ic_id')
            ->count('ic_id');

        $totalHandled = Service::whereNotNull('ic_id')->count();

        $activeServices = Service::whereNotNull('ic_id')
            ->whereIn('status_id', [4, 5])
            ->count();

        $avgDays = Service::whereNotNull('ic_id')
            ->whereNotNull('received_date')
            ->whereNotNull('finish_date')
            ->selectRaw('AVG(DATEDIFF(finish_date, received_date)) as avg_days')
            ->value('avg_days');

        $avgDaysFormatted = $avgDays ? number_format((float) $avgDays, 1) . ' hari' : '-';

        return [
            Section::make('Statistik PIC')
                ->description('Ringkasan Service / Memo IT')
                ->schema([
                    Stat::make('Total PIC', number_format($totalPic))
                        ->description('Jumlah PIC yang pernah ditugaskan')
                        ->icon('heroicon-o-users')
                        ->color('primary'),

                    Stat::make('Service Ditangani', number_format($totalHandled))
                        ->description('Total service yang memiliki PIC')
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->color('info'),

                    Stat::make('Sedang Dikerjakan', number_format($activeServices))
                        ->description('Status Barang di IT / Proses Service')
                        ->icon('heroicon-o-arrow-path')
                        ->color('warning'),

                    Stat::make('Rata-rata Penyelesaian', $avgDaysFormatted)
                        ->description('Dari received_date ke finish_date')
                        ->icon('heroicon-o-clock')
                        ->color('success'),
                ])
                ->columns(4)
                ->columnSpanFull(),
        ];
    }
}
