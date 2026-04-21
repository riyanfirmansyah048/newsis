<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BookingOrders\BookingOrderResource;
use App\Filament\Resources\Bppbs\BppbResource;
use App\Filament\Resources\Emails\EmailResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\BookingOrder;
use App\Models\Bppb;
use App\Models\Email;
use App\Models\Service;
use Filament\Schemas\Components\Section;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DashboardOverviewStats extends StatsOverviewWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $user = Auth::user();
        $isAdmin = $user?->hasRole('admin');
        $canManageBookingOrders = $user?->can('update-booking-order');

        $bppbQuery = Bppb::query();
        $bookingOrderQuery = BookingOrder::query();
        $serviceQuery = Service::query();
        $emailQuery = Email::query();

        if (! $isAdmin) {
            $bppbQuery->where('user_id', $user?->id);
            $serviceQuery->where('user_id', $user?->id);
            $emailQuery->where('idUser', $user?->id);
        }

        if (! $canManageBookingOrders) {
            $bookingOrderQuery->where('user_id', $user?->id);
        }

        $totalBppb = (clone $bppbQuery)->count();
        $pendingBppb = (clone $bppbQuery)->whereIn('status_id', [1, 3])->count();
        $completedBppb = (clone $bppbQuery)->whereIn('status_id', [6, 7])->count();

        $totalBookingOrders = (clone $bookingOrderQuery)->count();
        $pendingBookingOrders = (clone $bookingOrderQuery)->where('status', 'pending')->count();
        $approvedBookingOrders = (clone $bookingOrderQuery)->where('status', 'approved')->count();

        $totalServices = (clone $serviceQuery)->count();
        $openServices = (clone $serviceQuery)->whereNotIn('status_id', [6, 7])->count();
        $completedServices = (clone $serviceQuery)->whereIn('status_id', [6, 7])->count();

        $totalEmails = (clone $emailQuery)->count();
        $pendingEmails = (clone $emailQuery)->where('activeStatus', 0)->count();
        $activeEmails = (clone $emailQuery)->where('activeStatus', 1)->count();

        return [
            Section::make('Ringkasan Dashboard')
                ->description($isAdmin ? 'Ringkasan seluruh pengajuan yang perlu dipantau.' : 'Ringkasan pengajuan yang paling relevan untuk Anda.')
                ->schema([
                    Stat::make('BPPB', number_format($totalBppb))
                        ->description("Pending {$pendingBppb} • Selesai {$completedBppb}")
                        ->icon('heroicon-o-shopping-cart')
                        ->color('primary')
                        ->url(BppbResource::getUrl('index')),

                    Stat::make('Booking Order', number_format($totalBookingOrders))
                        ->description("Pending {$pendingBookingOrders} • Approved {$approvedBookingOrders}")
                        ->icon('heroicon-o-calendar-days')
                        ->color('warning')
                        ->url(BookingOrderResource::getUrl('index')),

                    Stat::make('Service', number_format($totalServices))
                        ->description("Open {$openServices} • Selesai {$completedServices}")
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->color('success')
                        ->url(ServiceResource::getUrl('index')),

                    Stat::make('Email', number_format($totalEmails))
                        ->description("Pending {$pendingEmails} • Active {$activeEmails}")
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->url(EmailResource::getUrl('index')),
                ])
                ->columns(4)
                ->columnSpanFull(),
        ];
    }
}
