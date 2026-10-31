<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Bppbs\BppbResource;
use App\Models\Bppb;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class BppbStatusStats extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $user = Auth::user();

        return [
            Stat::make(
                'Konfirmasi IT',
                Bppb::where('user_id', $user->id)
                    ->whereIn('status_id', [3])
                    ->count()
            )
                ->color('warning')
                ->description('Konfirmasi IT')
                ->icon('heroicon-o-clock')
                ->url(BppbResource::getUrl('index', [
                    'status_id' => 3,
                ])),

            Stat::make('Approved', Bppb::where('user_id', $user->id)
                ->where('status_id', 4)
                ->count())
                ->color('info')
                ->description('Approved')
                ->icon('heroicon-o-check-circle')
                ->url(BppbResource::getUrl('index', [
                    'status_id' => 4,
                ])),

            Stat::make('Rejected', Bppb::where('user_id', $user->id)
                ->where('status_id', 2)
                ->count())
                ->color('danger')
                ->description('Rejected')
                ->icon('heroicon-o-x-circle')
                ->url(BppbResource::getUrl('index', [
                    'status_id' => 2,
                ])),

            Stat::make('Barang di IT', Bppb::where('user_id', $user->id)
                ->whereIn('status_id', [5])
                ->count())
                ->color('primary')
                ->description('Barang di IT')
                ->icon('heroicon-o-check-badge')
                ->url(BppbResource::getUrl('index', [
                    'status_id' => 5,
                ])),

            Stat::make('Completed', Bppb::where('user_id', $user->id)
                ->whereIn('status_id', [6, 7])
                ->count())
                ->color('success')
                ->description('Completed')
                ->icon('heroicon-o-check-badge')
                ->url(BppbResource::getUrl('index', [
                    'status_id' => '6,7',
                ])),
        ];
    }

    protected function getHeading(): ?string
    {
        return 'Status BPPB';
    }

    protected function getColumns(): int|array|null
    {
        return 5;
    }
}
