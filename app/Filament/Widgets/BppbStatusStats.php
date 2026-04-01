<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingOrders\BookingOrderResource;
use App\Models\Bppb;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Section;
use Filament\Widgets\StatsOverviewWidget;
use App\Filament\Resources\Bppbs\BppbResource;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Filament\Resources\Emails\EmailResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Filament\Resources\Internets\InternetResource;

class BppbStatusStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $user = Auth::user();
        return [
            Section::make('Fast Access')
                ->schema([
                    Stat::make('Tambah BPPB', '')
                        ->url(BppbResource::getUrl('create'))
                        ->icon('heroicon-o-shopping-cart')
                        ->label('Tambah BPPB')
                        ->color('primary'),

                    Stat::make('Tambah Service', '')
                        ->url(ServiceResource::getUrl('create'))
                        ->icon('heroicon-o-wrench')
                        ->label('Tambah Service'),

                    Stat::make('Tambah Internet', '')
                        ->url(InternetResource::getUrl('create'))
                        ->icon('heroicon-o-globe-alt')
                        ->label('Tambah Internet'),

                    Stat::make('Pengajuan Email', '')
                        ->url(EmailResource::getUrl('create'))
                        ->icon('heroicon-o-envelope')
                        ->label('Pengajuan Email'),

                    Stat::make('Booking Order', '')
                        ->url(BookingOrderResource::getUrl('create'))
                        ->icon('heroicon-o-calendar-days')
                        ->label('Booking Order'),
                ])
                ->columns(5)
                ->columnSpanFull(),
            Section::make('Status BPPB')
                ->schema([
                    // Stat::make('Konfirmasi IT', Bppb::where('user_id', $user->id)
                    //     ->whereIn('status_id', [3])
                    //     ->count())
                    //     ->color('warning')
                    //     ->description('Konfirmasi IT')
                    //     ->icon('heroicon-o-clock'),

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
                            'status_id' => 3
                        ])),

                    Stat::make('Approved', Bppb::where('user_id', $user->id)
                        ->where('status_id', 4)
                        ->count())
                        ->color('info')
                        ->description('Approved')
                        ->icon('heroicon-o-check-circle')
                        ->url(BppbResource::getUrl('index', [
                            'status_id' => 4
                        ])),

                    Stat::make('Rejected', Bppb::where('user_id', $user->id)
                        ->where('status_id', 2)
                        ->count())
                        ->color('danger')
                        ->description('Rejected')
                        ->icon('heroicon-o-x-circle')
                        ->url(BppbResource::getUrl('index', [
                            'status_id' => 2
                        ])),

                    Stat::make('Barang di IT', Bppb::where('user_id', $user->id)
                        ->whereIn('status_id', [5])
                        ->count())
                        ->color('primary')
                        ->description('Barang di IT')
                        ->icon('heroicon-o-check-badge')
                        ->url(BppbResource::getUrl('index', [
                            'status_id' => 5
                        ])),

                    Stat::make('Completed', Bppb::where('user_id', $user->id)
                        ->whereIn('status_id', [6, 7])
                        ->count())
                        ->color('success')
                        ->description('Completed')
                        ->icon('heroicon-o-check-badge')
                        ->url(BppbResource::getUrl('index', [
                            'status_id' => '6,7'
                        ])),
                ])
                ->columns(5)
                ->columnSpanFull(),


        ];
    }
}
